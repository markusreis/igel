export const BROWSER_TYPE = {
    opera       : 'opera',
    firefox     : 'firefox',
    safari      : 'safari',
    ie          : 'ie',
    edge        : 'edge',
    chrome      : 'chrome',
    edgeChromium: 'edgeChromium',
    unknown     : 'unknown',
}

let browserType = null;

export function getBrowserType() {

    if (browserType !== null) {
        return browserType;
    }

    if ((navigator.userAgent.indexOf("Opera") || navigator.userAgent.indexOf('OPR')) !== -1) {
        return BROWSER_TYPE.opera;
    } else if (navigator.userAgent.indexOf("Firefox") !== -1) {
        return BROWSER_TYPE.firefox;
    } else if (navigator.userAgent.indexOf("Chrome") !== -1) {
        return BROWSER_TYPE.chrome;
    } else if (navigator.userAgent.indexOf("Safari") !== -1) {
        return BROWSER_TYPE.safari;
    } else if ((navigator.userAgent.indexOf("MSIE") !== -1) || (!!document.documentMode === true)) { // ie > 10
        return BROWSER_TYPE.ie;
    }
    return BROWSER_TYPE.unknown;
}
