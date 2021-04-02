const merge = require('util.merge-packages').default;
const fs = require('fs');

function findPackageJsonFiles() {
    let files = []
    if (fs.existsSync('../../Core/package.json'))
        files.push(fs.readFileSync('../../Core/package.json'));

    if (fs.existsSync('../../package.json'))
        files.push(fs.readFileSync('../../package.json'));
    const packageGroups = fs.readdirSync('../../Packages');
    for (let group of packageGroups) {
        const packages = fs.readdirSync('../../Packages/' + group);
        for (let pack of packages) {
            if (fs.existsSync(`../../Packages/${group}/${pack}/package.json`))
                files.push(fs.readFileSync(`../../Packages/${group}/${pack}/package.json`));
        }
    }
    return files;
}

function mergePackageJsonFiles(files) {
    let ret = files[0];
    for (let file of files.slice(1)) {
        ret = merge(ret, file);
    }
    return ret;
}

let files = findPackageJsonFiles();
let ret = mergePackageJsonFiles(files);
fs.writeFileSync('../Build/package.json', ret);