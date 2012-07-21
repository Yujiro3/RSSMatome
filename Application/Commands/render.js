var page = require('webpage').create(),
    system = require('system'),
    address, output, rect;

address = system.args[1];
output  = system.args[2];
rect    = system.args[3].split('x');

page.viewportSize = { width: 1200, height: 600 };
page.settings.userAgent = "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/536.11 (KHTML, like Gecko) Chrome/20.0.1132.47 Safari/536.11";
page.clipRect = { left: rect[0], top: rect[1], width: rect[2], height: rect[3] } 
page.open(address, function (status) {
    if (status !== 'success') {
        console.log('Unable to load the address!');
    } else {
        window.setTimeout(function () {
            page.render(output);
            phantom.exit();
        }, 200);
    }
});

