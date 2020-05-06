document.getElementById('addCollectionCTA').addEventListener("click", () => {

    gsap.fromTo("#collection-form", .5, {
        display:"none",
        height:"0px"
    },
    {
        height:"100vh",
        display:"block",
        position:"fixed",
        bottom:0,
        padding:"100px 1.5rem 1.5rem 1.5rem",
        ease: Power2.easeOut
    });

}, false);

document.getElementById('close-collection-form').addEventListener("click", () => {

    gsap.to("#collection-form", .5, {        
        height:"0vh",
        padding:0,
        ease: Power2.easeOut
    });


}, false);
