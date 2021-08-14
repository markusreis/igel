import imagesLoaded from 'imagesloaded'
import WebFont from 'webfontloader';

// Preload images
const preloadImages = (images) => {
    return new Promise((resolve) => {
        imagesLoaded(images, resolve)
            .on('progress', window.preload.progress);
    });
};

// Preload fonts
const preloadFonts = (families) => {
    return new Promise((resolve) => {
        WebFont.load(Object.assign(
            {
                classes   : false,
                fontactive: window.preload.progress,
                active    : resolve,
                timeout   : 2000
            },
            families
        ));
    });
};

export const preload = ({onProgress = ({total, current, p}) => {}, container = document.documentElement}) => {

    document.documentElement.classList.remove('preloader-loaded')
    document.documentElement.classList.add('preloader-loading')
    window.isMobile = /Android|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent) || navigator.platform === 'MacIntel' && navigator.maxTouchPoints > 1;
    document.documentElement.classList.add(window.isMobile ? '-mobile' : '-desktop')

    const images = container.querySelectorAll('img');

    const fonts = {
        google: {
            families: ['Lato:300,400,700,900']
        },
    }

    // Add more Loading Steps
    const additionalPromises = [];
    const additionalLoadingSteps = 0;

    const totalSteps = images.length +
        (fonts.google && fonts.google.families ? fonts.google.families.length : 0) +
        (!!fonts.typekit && !!fonts.typekit.id ? fonts.typekit.id.split(',').length : 0) +
        additionalLoadingSteps

    window.preload = {
        progress: () => {
            window.preload.current++
            onProgress({
                           total  : totalSteps,
                           current: window.preload.current,
                           p      : window.preload.current / totalSteps
                       })
        },
        steps   : totalSteps,
        current : 0,
    }

    return Promise.all([preloadImages(images), preloadFonts(fonts), ...additionalPromises]).then(() => {
        document.documentElement.classList.remove('preloader-loading')
        document.documentElement.classList.add('preloader-loaded')
    })
}