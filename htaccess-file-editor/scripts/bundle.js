const fs = require("fs");
const path = require("path");
const archiver = require("archiver");

const pluginSlug = "htaccess-file-editor";
const pluginFolder = "htaccess-file-editor";
const version = require("../package.json").version;

const output = fs.createWriteStream(
  path.join(__dirname, `../${pluginFolder}-${version}.zip`)
);
const archive = archiver("zip", {
  zlib: { level: 9 }
});

output.on("close", function () {
  console.log(archive.pointer() + " total bytes");
  console.log(
    "Archive has been finalized and the output file descriptor has closed."
  );
});
archive.on("error", function (err) {
  throw err;
});

archive.pipe(output);

archive.directory("build/includes/", `${pluginFolder}/includes`);
archive.directory("build/assets/", `${pluginFolder}/assets`);
archive.directory("build/languages/", `${pluginFolder}/languages`);
archive.directory("build/templates/", `${pluginFolder}/templates`);
archive.file("build/htaccess-file-editor.php", { name: `${pluginFolder}/htaccess-file-editor.php` });
archive.file("build/index.php", { name: `${pluginFolder}/index.php` });
archive.file("build/readme.txt", { name: `${pluginFolder}/readme.txt` });
archive.file("build/changelog.txt", { name: `${pluginFolder}/changelog.txt` });
archive.file("build/license.txt", { name: `${pluginFolder}/license.txt` });

archive.finalize();
