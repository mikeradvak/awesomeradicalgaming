<?php
/*
Template Name: Random Adventure
*/
?>
<?php
// If you are using The Loop inside your own design (and your own design is not a template), set WP_USE_THEMES to false.
define('WP_USE_THEMES', false);
// Register the $more global.
global $more;
// Start the loop.
if ( have_posts() ) : while ( have_posts() ) : the_post();
		$more = 1;
		?>
<div style="background-color: #000; width: 100%;">
<div class="entry" style="margin: 0 auto; width: 448px;">
	<canvas id="canvas" height="512" width="448">
		Canvas not supported.
	</canvas>

	<?php the_content(); ?>
</div></div>
		<?php
	endwhile;
endif;
?>
<script type="text/javascript">
	// Get canvas object, set context and game.
	var canvas, canvas_context, canvas_context_tmp, canvas_image_data, canvas_tmp;
	var height		= 256;
	var height_scaled	= 512;
	var game		= new Game();
	var width		= 224;
	var width_scaled	= 448;
	
	// Start the game.
	game.initialize();
	
	// Game class.
	function Game() {
		// Game attributes.
		var background_image;
		var game_object;
		var player;
		var update_interval;
		
		// Clear the context.
		this.clear = function () {
			canvas_context_tmp.clearRect (0, 0, width , height);
		}
		
		// Draw a frame.
		this.draw = function () {
			// Get the player information.
			var player_dimensions	= player.getDimensions();
			var player_position		= player.getPosition();
			var player_source_point	= player.getSourcePoint();
			// Draw the background image.
			// NOTE: All drawing done on the tmp image.
			// TODO: Really should be removed, pulled into a class.
			canvas_context_tmp.drawImage(background_image, 0, 0, width, height, 0, 0, width, height);
			// Draw the player on the context. For reference:
			// drawImage(imageObj, sourceX, sourceY, sourceWidth, sourceHeight, destX, destY, destWidth, destHeight);
			canvas_context_tmp.drawImage(player.getImage(), player_source_point[0], player_source_point[1], player_dimensions[0], player_dimensions[1], player_position[0], player_position[1], player_dimensions[0], player_dimensions[1]);
			// Set the canvas from the tmp image.
			canvas_context.drawImage(canvas_tmp, 0, 0, width, height, 0, 0, width_scaled, height_scaled);
		}
		
		// Initialize game attributes, start the game.
		this.initialize = function () {
			canvas = document.getElementById('canvas')
			// Check whether browser supports getting canvas context.
			if (canvas && canvas.getContext) {
				// Setup the background image.
				// TODO: Really should be removed, pulled into a class.
				background_image		= new Image();
				background_image.src	= 'http://awesomeradicalgaming.com/wp-content/uploads/2012/02/back_0_0.png';
				// Set the context as 2D.
				canvas_context			= canvas.getContext('2d');
				// Create a off-screen canvas to draw on.
				canvas_tmp				= document.createElement('canvas');
				canvas_tmp.width		= width;
				canvas_tmp.height		= height;
				canvas_context_tmp		= canvas_tmp.getContext('2d');
				// This is a game object.
				game_object				= this;
				// Initialize the player.
				player					= new Player();
				player.initialize();
				// Set the update interval and begin the game loop.
				update_interval			= 100;
				this.game_loop			= setInterval(this.runGameLoop, update_interval);
			}
		}
		
		// Update between frames.
		this.update = function () {
			// Update the player frame, position.
			player.updateFrameCurrent();
			player.updatePosition([width, height]);
		}
		
		// The game loop, update then draw.
		this.runGameLoop = function (game) {
			game_object.update();
			game_object.clear();
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
		
		// Initialize the attributes.
		this.initialize = function () {
			dimensions		= [5,9];
			frame_current		= 1;
			frame_total		= 1;
			image			= new Image();
			image.src		= 'http://awesomeradicalgaming.com/wp-content/uploads/2012/02/guy.png';
			initialized		= true;
			position		= [0,0];
			speed			= 5;
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
			if ((position[0] + dimensions[0] + speed) > canvas_size[0]) {
				position[0] = 0;
				if ((position[1] + dimensions[1] + speed) > canvas_size[1]) {
					position[1] = 0;
				} else {
					position[1] = position[1] + speed;
				}
			} else {
				position[0] = position[0] + speed;
			}
		}
	}
</script>
