// Load plugins
const browsersync = require("browser-sync").create();
const gulp = require("gulp");
const plumber = require("gulp-plumber");
const uglify = require("gulp-uglify");


// BrowserSync
function browserSync(done) {
	browsersync.init({
		proxy: "http://192.168.10.10/sample_bukubuku/"
	});
	done();
}

// BrowserSync Reload
function browserSyncReload(done) {
	browsersync.reload();
	done();
}

function watchFiles() {
	gulp.watch("../**/*.php", browserSyncReload);
	gulp.watch("../src/css/**/*", browserSyncReload);
	gulp.watch("../src/js/**/*", browserSyncReload);
}


// dev task
gulp.task("default", gulp.parallel(watchFiles, browserSync));
