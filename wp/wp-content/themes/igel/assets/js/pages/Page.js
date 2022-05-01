import {gsap} from "gsap";
import {initContactForms} from "../handlers/contact";
import {initAccordion} from "../handlers/accordion";

export class Page {
    constructor(props) {
        this.app = props.app
    }

    create() {

        setTimeout(() => {
            initContactForms(this.app)
            initAccordion()
        }, 300)

        const tl = gsap.timeline()

        const preloadOverlay = document.getElementById('preload')
        const heroOverlay = document.querySelector('.c-hero__overlay')
        const preTitle = document.querySelector('.text-pretitle')
        const title = document.querySelector('.text-title')
        const box = document.querySelector('.c-hero__box-wrap')
        const boxOverlay = document.querySelector('.c-hero__box__overlay')
        const main = document.querySelector('#main')
        const sections = document.querySelectorAll('section')
        const footer = document.querySelector('#colophon')

        main.style.opacity = 0;
        footer.style.opacity = 0;
        Array.from(sections).forEach(e => e.style.opacity = 0)

        tl.to(preloadOverlay, {
            opacity: 0,
            duration: 0.1
        })
        tl.to(heroOverlay,
            {
                height: 0,
                duration: 1.5,
                ease: 'Power4.easeOut'
            }, "<20%")
        tl.fromTo(preTitle,
            {
                opacity: 0,
                y: 75,
            },
            {
                opacity: 1,
                y: 0,
                duration: 1.25,
                ease: 'Power4.easeOut'
            }, "<2%")
        tl.fromTo(title,
            {
                opacity: 0,
                y: 75,
            },
            {
                opacity: 1,
                y: 0,
                duration: 1.25,
                ease: 'Power4.easeOut'
            }, "<4%")
        if (!!box) {
            tl.fromTo(box,
                {
                    opacity: 0,
                    y: 0,
                },
                {
                    opacity: 1,
                    y: window.innerWidth >= 768 ? -50 : -15,
                    duration: 1.5,
                    ease: 'Power4.easeOut'
                }, "<5%")
        }
        if (!!boxOverlay) {
            tl.to(boxOverlay,
                {
                    height: 0,
                    duration: 1.5,
                    ease: 'Power4.easeOut'
                }, "<5%")
        }
        if (!!main) {
            tl.to(main,
                {
                    opacity: 1,
                    duration: 2,
                    ease: 'Power4.easeOut'
                }, "<25%")
        }
        if (!!sections) {
            tl.to(sections,
                {
                    opacity: 1,
                    duration: 2,
                    ease: 'Power4.easeOut'
                }, "<0%")
        }
        if (!!footer) {
            tl.to(footer,
                {
                    opacity: 1,
                    duration: 2,
                    ease: 'Power4.easeOut'
                }, "<0%")
        }

        return tl
    }

    leave() {

        const tl = gsap.timeline()
        const heroOverlay = document.querySelector('.c-hero__overlay')
        const preTitle = document.querySelector('.text-pretitle')
        const title = document.querySelector('.text-title')
        const box = document.querySelector('.c-hero__box-wrap')
        const boxOverlay = document.querySelector('.c-hero__box__overlay')
        const main = document.querySelector('#main')
        const footer = document.querySelector('#colophon')
        const preloadOverlay = document.getElementById('preload')
        const sections = document.querySelectorAll('section')


        heroOverlay.style.top = 'auto';
        heroOverlay.style.bottom = 0;

        if (boxOverlay) {
            boxOverlay.style.bottom = 'auto';
            boxOverlay.style.top = 0;
        }

        const ease = 'Power3.easeIn'

        tl.to(heroOverlay,
            {
                height: '100%',
                duration: 0.875,
                ease: ease
            })
        tl.to(preTitle,
            {
                opacity: 0,
                y: -75,
                duration: 0.75,
                ease: ease
            }, "<2%")
        tl.to(title,
            {
                opacity: 0,
                y: -75,
                duration: 0.75,
                ease: ease
            }, "<4%")
        if (!!box) {
            tl.to(box,
                {
                    opacity: 0,
                    y: window.innerWidth >= 768 ? -80 : -30,
                    duration: 0.75,
                    ease: ease
                }, "<0%")
        }
        if (!!boxOverlay) {
            tl.to(boxOverlay,
                {
                    height: '100%',
                    duration: 0.75,
                    ease: ease
                }, "<0%")
        }
        if (!!main) {
            tl.to(main,
                {
                    opacity: 0,
                    duration: 1,
                    ease: ease
                }, "<0%")
        }
        if (!!sections) {
            tl.to(sections,
                {
                    opacity: 0,
                    duration: 1,
                    ease: ease
                }, "<0%")
        }
        if (!!footer) {
            tl.to(footer,
                {
                    opacity: 0,
                    duration: 1,
                    ease: ease
                }, "<0%")
        }
        tl.to(preloadOverlay, {
            opacity: 1,
            duration: 0.05
        })

        return tl
    }
}