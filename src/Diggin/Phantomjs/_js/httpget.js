if (phantom.state.length === 0) {
    if (phantom.args.length < 2) {
        phantom.exit();
    }

    var userAgent = phantom.args[0];
    var url = phantom.args[1];
    phantom.state = 'checking';
    phantom.userAgent = userAgent;
    phantom.open(url);
} else {
    //console.log(document.getElementsByTagName('html')[0].outerHTML);
    console.log(phantom.content);
    phantom.exit();
}
