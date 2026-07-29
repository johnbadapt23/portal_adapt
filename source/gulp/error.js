var log = require('fancy-log');

module.exports = {
	handler: function( error ) {
		log.error('Error: ' + error.toString());
		process.stdout.write('\x07'); // terminal bell, replaces gulp-util's gutil.beep()
		this.emit( 'end' );
	}
};
