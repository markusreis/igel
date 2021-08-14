import {Page} from "./Page";
import {Tabs} from "../handlers/tab";
import {initContactForms} from "../handlers/contact";
import {initAccordion} from "../handlers/accordion";
import {gsap} from "gsap";
import {initGallery} from "../modules/Gallery";

export class NewBuild extends Page {

    constructor(props) {
        super(props)
    }

    create() {
        setTimeout(() => {
            console.log(Array.from(document.querySelectorAll('.c-tabs')))
            Array.from(document.querySelectorAll('.c-tabs')).forEach(e => new Tabs(e))
            initGallery()
            initContactForms()
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
            opacity : 0,
            duration: 0.1
        })
        tl.to(heroOverlay,
              {
                  height  : 0,
                  duration: 1.5,
                  ease    : 'Power4.easeOut'
              }, "<20%")
        if (!!preTitle) {
            tl.fromTo(preTitle,
                      {
                          opacity: 0,
                          y      : 75,
                      },
                      {
                          opacity : 1,
                          y       : 0,
                          duration: 1.25,
                          ease    : 'Power4.easeOut'
                      }, "<2%")
        }
        if (!!title) {
            tl.fromTo(title,
                      {
                          opacity: 0,
                          y      : 75,
                      },
                      {
                          opacity : 1,
                          y       : 0,
                          duration: 1.25,
                          ease    : 'Power4.easeOut'
                      }, "<4%")
        }
        tl.to(boxOverlay,
              {
                  height  : 0,
                  duration: 1.5,
                  ease    : 'Power4.easeOut'
              }, "<5%")
        tl.to(main,
              {
                  opacity : 1,
                  duration: 2,
                  ease    : 'Power4.easeOut'
              }, "<25%")
        tl.to(sections,
              {
                  opacity : 1,
                  duration: 2,
                  ease    : 'Power4.easeOut'
              }, "<0%")
        tl.to(footer,
              {
                  opacity : 1,
                  duration: 2,
                  ease    : 'Power4.easeOut'
              }, "<0%")

        return tl
    }

    leave() {
        const modal = document.querySelector('.c-gallery__modal')
        if (!!modal) {
            modal.remove()
        }
        return super.leave();
    }
}