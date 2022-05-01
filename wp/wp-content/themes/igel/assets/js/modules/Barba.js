import barba from '@barba/core';
import {gsap} from "gsap";

const curved = 'M 100 0 L 0 0 Q 15 15 50 15 Q 85 15 100 0'
const curvedLess = 'M 100 0 L 0 0 Q 5 5 50 5 Q 95 5 100 0'
const flat = 'M 100 0 L 0 0 Q 20 0 50 0 Q 80 0 100 0 '

export const initBarba = (app) => {
    barba.init({
        debug: true,
        transitions: [{
            name: 'default-transition',

            afterEnter() {
                setTimeout(() => app.loadCurrentPage(), 50)

                if (Cookiebot?.consent?.statistics) {
                    setTimeout(() => {
                        gtag('event', 'page_view', {
                            page_title: document.querySelector('title').innerText,
                            page_location: window.location.href,
                            page_path: location.pathname,
                            send_to: 'G-HCNNW15GYB'
                        })
                    }, 500)
                }
            },

            beforeEnter(data) {
                if (window.isMobile) {
                    window.scrollTo(0, 0)
                } else {
                    window.scrollTo({
                        top: 0,
                        left: 0,
                        behavior: 'smooth'
                    })
                }
            },

            leave() {
                setTimeout(() =>
                        document.getElementById('toggle-nav').checked = false
                    , 200)
                return app.leaveCurrentPage()
            }
        }]
    })
}


function t1() {
    return {
        in: () => {
            //gsap.to('#path path', {attr: {d: end}, duration: 500})
            const tl = gsap.timeline()
            const stagger = 0.07;

            // RESET
            tl.to('.c-transition .c-transition__svg.c-transition__svg--down path', {
                attr: {d: flat},
                duration: 0
            })
                .to('#transition-brand', {rotate: 0, duration: 0})
                .to('.c-transition', {y: '0vh', duration: 0})
                .to('.c-transition .c-transition__clear-svg path', {
                    attr: {d: curved},
                    duration: 0
                })

            // ANIMATION
            tl.to('.c-transition .c-transition__svg.c-transition__svg--down path', {
                attr: {d: curved}, duration: .25
            })
            tl.to('.c-transition', {
                y: '100vh',
                duration: 1,
                ease: "power4.in"
            }, "-=.7")
            tl.to('.c-transition .c-transition__svg.c-transition__svg--down path', {
                attr: {d: flat},
                duration: .2
            }, '-=.1')

            const brandLogoTl = gsap.timeline()
            brandLogoTl.to('#transition-brand', {
                scale: 1,
                duration: .5,
                ease: "elastic.out(1.5,0.75)",
                delay: .6
            })
            brandLogoTl.to('#transition-brand', {
                rotate: 360,
                ease: "elastic.inOut(1,0.75)",
                duration: 1.25
            }, '-=.35')

            return brandLogoTl
        },
        out: () => {
            const tl = gsap.timeline()
            tl.to('#transition-brand', {
                scale: 0,
                duration: .5,
                ease: "back.in(2)",
            })
            tl.to('.c-transition', {
                //stagger : 0.02,
                y: '200vh',
                duration: 1.5,
                ease: "power3.in"
            }, "-=1")
            tl.to('.c-transition .c-transition__clear-svg path', {
                attr: {d: flat},
                duration: .2
            }, "-=.1")
        }
    }
}

function t2() {
    return {
        out: () => {
            window.scrollTo(0, 0)

            if (window.isMobile && document.getElementById('toggle-nav').checked) {
                document.querySelector('.header label[for="toggle-nav"]').click()
            }

            gsap.to('.c-transition-2', {
                //stagger   : -.025,
                skewX: '0deg',
                translateX: '100%',
                ease: 'power2.in',
                duration: .3,
                delay: .15
            })
        },
        in: () => {
            // RESET
            gsap.to('.c-transition-2', {
                skewX: '30deg',
                translateX: '-100%',
                duration: 0
            })

            // ANIMATE
            return gsap.to('.c-transition-2', {
                stagger: .1,
                skewX: 0,
                translateX: 0,
                ease: 'power3.in',
                duration: .5
            })
        }
    }
}