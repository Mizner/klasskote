var project = "klasskote";

var jsFilePath = "assets/scripts/";
var theFiles = orderJsFiles([
    // 1st file loads first, 2nd, ect.
    "skip-link-focus-fix.js",
    "navigation.js",
    "navigation-buttons.js",
    "mizpops.js",
    "main.js",
    "woo-product-cat-toggles.js"
]);

// Provide the ability to simplify ordering scripts for concatenation.  See: var theFiles above
function orderJsFiles(arr) {
    return arr.map(function (str) {
        return jsFilePath + str
    })
}

var gulp = require("gulp"),
    browserSync = require("browser-sync").create(),
    autoprefixer = require("gulp-autoprefixer"),
    cssnano = require("gulp-cssnano"),
    uglify = require("gulp-uglify"),
    rename = require("gulp-rename"),
    concat = require("gulp-concat"),
    sass = require("gulp-sass"),
    sourcemaps = require("gulp-sourcemaps");

gulp.task("sass", function () {
    gulp.src("./assets/styles/**/*.scss")
        .pipe(sass().on("error", sass.logError))
        .pipe(autoprefixer())
        .pipe(sourcemaps.init({loadMaps: true}))
        .pipe(sourcemaps.write(""))
        .pipe(rename(project + ".min.css"))
        .pipe(gulp.dest("./dist"))
        .pipe(browserSync.stream())
});

gulp.task("js", function () {
    gulp.src(theFiles)
        .pipe(concat("output.min.js")) // concat pulls all our files together before minifying them
        .pipe(uglify())
        .pipe(rename(project + ".min.js"))
        .pipe(gulp.dest("./dist"))
});


// gulp.task("production-sass", function () {
//     gulp.src("assets/style/**/*.scss")
//         .pipe(sass().on("error", sass.logError))
//         .pipe(autoprefixer())
//         .pipe(cssnano())
//         .pipe(rename("style.min.css"))
//         .pipe(gulp.dest("./dist"));
// });

gulp.task("browser-sync", function () {
    browserSync.init(["*"], {
        proxy: project + ".dev",
        root: [__dirname],
        open: {
            file: "index.php"
        }
    });
});


gulp.task("watch", ["browser-sync"], function () {
    gulp.watch("./assets/styles/**/*.scss", ["sass"]);
    gulp.watch("assets/scripts/*", ["js"]);
    gulp.watch("./assets/scripts/**/*.js", browserSync.reload);
    gulp.watch("./**/*.php", browserSync.reload);
    gulp.watch("gulpfile.js").on("change", function () {
        process.exit(0)
    })
});

gulp.task("default", ["sass", "js"]);

gulp.task("production", ["production-sass", "js"]);