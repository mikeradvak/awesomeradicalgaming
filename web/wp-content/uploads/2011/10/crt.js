// Get canvas object, set context and game.
var _canvas		= document.getElementById('canvas');
var _canvasContext	= null;
var game		= new Game();
var crt_filter		= false;

// Check whether browser supports getting canvas context.
if (_canvas && _canvas.getContext) {
	game.initialize();
}

// Turn on/off the CRT filter.
function crtEngage() {
	crt_filter = crt_filter ? false : true;
	if (crt_filter) {
		document.getElementById('crt_link').innerHTML = 'CRT Filter On';
	} else {
		document.getElementById('crt_link').innerHTML = 'CRT Filter Off';
	}
}

// CRT class.
function Crt () {
	var phosphor_bleed;
	var phosphor_bloom;
	var phosphor_bloom_linspace;
	var scale_add;
	var scale_times;
	var scan_lower_limit;
	var scan_range;
	var scan_upper_limit;
	// Initialize the attributes.
	this.initialize = function () {
		phosphor_bleed		= 0.78;
		phosphor_bloom		= [];
		phosphor_bloom_linspace	= [];
		scale_add		= 1;
		scale_times		= 0.8;
		scan_lower_limit	= 0.5;
		scan_range		= [];
		scan_upper_limit	= 0.65;
		var i = 0;
		for (i = 0; i < 256; i++) {
			phosphor_bloom_linspace[i] = i / (255);
		}
		for (i = 0; i < 256; i++) {
			phosphor_bloom[i] = (scale_times * phosphor_bloom_linspace[i] ^ (1 / 2.2)) + scale_add;
		}
		var current	= scan_lower_limit;
		var step	= (scan_upper_limit - scan_lower_limit) / 256;
		for (i = 0; i < 256; i++) {
			current += step;
			scan_range[i] = current;
		}
	}
	this.filter = function (canvas_image_data) {
		var x, y;
		var new_data	= _canvasContext.createImageData(_canvas.width, _canvas.height);
		var new_image	= new CanvasImageData(new_data);
		for (y = 0; y < _canvas.height; y++) {
			for (x = 0; x < _canvas.width; x++) {
				var current_pixel_data = canvas_image_data.getPixel(x, y);
				// Handle red data.
				if (x % 2 == 0) {
					var red = current_pixel_data[1];
				} else {
					var previous_pixel_data = canvas_image_data.getPixel(x - 1, y);
					if (previous_pixel_data[1] > 0) {
						var red = previous_pixel_data[1] * phosphor_bleed * phosphor_bloom[previous_pixel_data[1]];
					} else {
						var red = current_pixel_data[1];
					}
				}
				// Handle green data.
				if (current_pixel_data[2] > 0) {
					var green = (current_pixel_data[2] / 2) + (current_pixel_data[2] / 2 * phosphor_bleed * phosphor_bloom[current_pixel_data[2]]);
				} else {
					var green = current_pixel_data[2];
				}
				// Handle blue data.
				if (x < 1) {
					var blue = current_pixel_data[3];
				} else if (x == 1) {
					if (current_pixel_data[3] > 0) {
						var previous_pixel_data = canvas_image_data.getPixel(x - 1, y);
						var blue = current_pixel_data[3] * phosphor_bleed * phosphor_bloom[current_pixel_data[3]]
						new_image.setPixel(x - 1, y, [previous_pixel_data[0], previous_pixel_data[1], previous_pixel_data[2], blue]);
					}
					var blue = current_pixel_data[3];
				} else if (x > 1) {
					if (x % 2 == 0) {
						var blue = current_pixel_data[3];
					} else {
						var blue = current_pixel_data[3] * phosphor_bleed * phosphor_bloom[previous_pixel_data[3]];
					}
				}
				// Handle scan lines.
				if (y % 2 == 0) {
					var next_pixel_data	= canvas_image_data.getPixel(x, y + 1);
					var red			= scan_range[current_pixel_data[1] + 1] * current_pixel_data[1];
					var green		= scan_range[current_pixel_data[2] + 1] * current_pixel_data[2];
					var blue		= scan_range[current_pixel_data[3] + 1] * current_pixel_data[3];
					new_image.setPixel(x, y + 1, [next_pixel_data[0], red, green, blue]);
				}
				new_image.setPixel(x, y, [current_pixel_data[0], red, green, blue]);
			}
		}
		return new_image;
	}
}

/**
 * When you get image data from a canvas element's 2D context you're left with
 * a one-dimensional array contanining four items for every pixel in the image
 * (opacity, and red, green, and blue values). This means it's very fast to
 * access the data but that your head will hurt when trying to work out what
 * pixel is where.
 *
 * CanvasImageData makes it much easier for you to work with image data. It
 * provides methods to get and set individual pixel data, and to convert x and
 * y coordinates to the correct index.
 *
 * @constructor
 * @param imageData The base image data.
 */
var CanvasImageData = function (imageData) {
	this.width = imageData.width;
	this.height = imageData.height;
	this.data = imageData.data;
	this.imageData = imageData;
};
CanvasImageData.prototype = {
	/**
	 * Convert a pair of x and y coordinates to the index of the first of the
	 * four elements for that particular pixel in the image data. For example,
	 * if you have a 100x100 image and you pass in x=34, y=56, the function
	 * will return 22536, the position of the opacity value for the pixel at
	 * position (34, 56).  The red, green, and blue values will be the
	 * following three elements in the array.
	 *
	 * @param {int} x The horizontal position of the pixel.
	 * @param {int} y The vertical position of the pixel.
	 * @throws CoordinateError The x or y coordinate is out of range.
	 * @return Index of the pixel's first element within the image data array.
	 * @type int
	 */
	convertCoordsToIndex: function (x, y) {
		if (x < 0 || x > this.width || y < 0 || y > this.height) {
			throw CoordinateError;
		}
		return (y * this.width * 4) + (x * 4);
	},

	/**
	 * Returns a four-element array containing the opacity, red, green, and
	 * blue values for the pixel at the given x, y position.
	 *
	 * @param {int} x The horizontal position of the pixel.
	 * @param {int} y The vertical position of the pixel.
	 * @return Array of opacity, red, green, and blue values for the pixel.
	 * @type Array
	 */
	getPixel: function (x, y) {
		var pos;
		try {
			pos = this.convertCoordsToIndex(x, y);
			return [
				this.imageData.data[pos],
				this.imageData.data[pos + 1],
				this.imageData.data[pos + 2],
				this.imageData.data[pos + 3]
			];
		}
		catch (e) {
			return false;
		}
	},

	/**
	 * Sets the opacity, red, green, and blue values for the pixel at the given
	 * x, y position.
	 *
	 * @param {int} x The horizontal position of the pixel.
	 * @param {int} y The vertical position of the pixel.
	 * @param {Array} pixelValues Opacity, red, green, and blue values.
	 */
	setPixel: function (x, y, pixelValues) {
		var pos;
		try {
			pos = this.convertCoordsToIndex(x, y);
		}
		catch (e) {
			return false;
		}
		this.imageData.data[pos] = pixelValues[0];
		this.imageData.data[pos + 1] = pixelValues[1];
		this.imageData.data[pos + 2] = pixelValues[2];
		this.imageData.data[pos + 3] = pixelValues[3];
	}
};















// Game class.
function Game() {
	// Game attributes.
	var crt;
	var game_object;
	var player;
	var update_interval;
	// Draw a frame.
	this.draw = function () {
		// Clear the context.
		// TODO: Clear?
		_canvasContext.fillStyle   = '#FFF';
		_canvasContext.fillRect(0, 0, _canvas.width, _canvas.height);
		// Get the player information.
		var player_dimensions	= player.getDimensions();
		var player_position	= player.getPosition();
		var player_source_point	= player.getSourcePoint();
		// Draw the player on the context.
		// For reference:
		// drawImage(imageObj, sourceX, sourceY, sourceWidth, sourceHeight, destX, destY, destWidth, destHeight);
		image			= new Image();
		image.src		= 'http://awesomeradicalgaming.com/wp-content/uploads/2011/10/back.png';
		_canvasContext.drawImage(image, 0, 0, 500, 250, 0, 0, 500, 250);
		_canvasContext.drawImage(player.getImage(), player_source_point[0], player_source_point[1], player_dimensions[0], player_dimensions[1], player_position[0], player_position[1], player_dimensions[0], player_dimensions[1]);
		
		
		
		
		
		
		
		
					
		if (crt_filter) {
			canvasImageData = new CanvasImageData(_canvasContext.getImageData(0, 0,_canvas.width, _canvas.height));
			_canvasContext.putImageData(crt.filter(canvasImageData).imageData, 0, 0);
		}
	}
	// Initialize game attributes, start the game.
	this.initialize = function () {
		// Set the context as 2D.
		_canvasContext		= _canvas.getContext('2d');
		crt			= new Crt();
		crt.initialize();
		game_object		= this;
		player			= new Player();
		player.initialize();
		update_interval		= 100;
		this.game_loop		= setInterval(this.runGameLoop, update_interval);
	}
	// Update between frames.
	this.update = function () {
		// Update the player frame, position.
		player.updateFrameCurrent();
		player.updatePosition([_canvas.width, _canvas.height]);
	}
	// The game loop, update then draw.
	this.runGameLoop = function (game) {
		game_object.update();
		game_object.draw();
	}
}
// Player class.
function Player () {
	// Player attributes.
	var dimensions;
	var frame_current;
	var frame_total;
	var image;
	var initialized = false;
	var position;
	var speed;
	// Initialize the attributes.
	this.initialize = function () {
		dimensions		= [6,7];
		frame_current	= 1;
		frame_total		= 5;
		image			= new Image();
		image.src		= 'http://awesomeradicalgaming.com/wp-content/uploads/2011/10/test2.png';
		initialized		= true;
		position		= [0,0];
		speed			= 5;
	}
	// Get the dimensions.
	this.getDimensions = function () {
		return dimensions;
	}
	// Get the image.
	this.getImage = function () {
		return image;
	}
	// Get the position.
	this.getPosition = function () {
		return position;
	}
	// Get the source point on the image (for animation).
	this.getSourcePoint = function () {
		var y_multiplier_helper = Math.round(frame_current / 2);
		if ((frame_current % 2) == 0) {
			var x	= dimensions[0];
			var y	= (frame_current - (y_multiplier_helper + 1)) * dimensions[1];
		} else {
			var x	= 0;
			var y	= (frame_current - y_multiplier_helper) * dimensions[1];
		}
		return [x,y];
	}
	// Update the current frame.
	this.updateFrameCurrent = function () {
		if (frame_current < frame_total) {
			frame_current = frame_current + 1;
		} else {
			frame_current = 1;
		}
	}
	// Update the position.
	this.updatePosition = function (canvas_size) {
		if ((position[0] + speed) > (canvas_size[0] - dimensions[0])) {
			position[0] = 0;
			if (position[1] >= (canvas_size[1] - dimensions[1])) {
				position[1] = 0;
			} else {
				position[1] = position[1] + speed;
			}
		} else {
			position[0] = position[0] + speed;
		}
	}
}
