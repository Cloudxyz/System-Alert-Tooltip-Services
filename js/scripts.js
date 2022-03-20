function randomDate(start, end) {
    return new Date(start.getTime() + Math.random() * (end.getTime() - start.getTime()));
}

function fadeInEffect(element) {
    let fadeTarget = element;
    let opacity = 0;
    let intervalID = setInterval(() => {
        fadeTarget.style.display = 'block';
        if (opacity < 1) {
            opacity = opacity + 0.1
            fadeTarget.style.opacity = opacity;
        } else {
            clearInterval(intervalID);
        }
    }, 20);
}

function fadeOutEffect(element) {
    let fadeTarget = element;
    let opacity = 1;
    let intervalID = setInterval(() => {
        if (opacity > 0) {
            opacity = opacity - 0.1
            fadeTarget.style.opacity = opacity;
        } else {
            fadeTarget.style.display = 'none';
            clearInterval(intervalID);
        }
    }, 20);
}

Date.prototype.subHours = function(h) {
    this.setTime(this.getTime() - (h * 60 * 60 * 1000));
    return this;
}

var services = [];
var delay = 5000;
setTimeout(() => {
    services = document.getElementsByClassName('as-list-services')[0].innerHTML.split(',');
    delay = document.getElementsByClassName('as-delay')[0].innerHTML;
}, 100);

setInterval(() => {
    const getRnadomDate = randomDate(new Date().subHours(10), new Date());
    let randomHour = getRnadomDate.getHours().toString().padStart(2, '0') + ':' + getRnadomDate.getMinutes().toString().padStart(2, '0') + ':' + getRnadomDate.getSeconds().toString().padStart(2, '0');
    document.getElementsByClassName('date-notification')[0].innerHTML = randomHour;
    document.getElementsByClassName('location-notification')[0].innerHTML = services[Math.floor(Math.random() * services.length)];
    fadeInEffect(document.getElementsByClassName('container-notification')[0]);
    setTimeout(() => {
        fadeOutEffect(document.getElementsByClassName('container-notification')[0]);
    }, 3000);
}, parseInt(delay));