window.convertTime = function (time24) {
    // Split the time string into parts
    let timeParts = time24.split(':').map(Number);

    // Extract hours, minutes, and optional seconds
    let hours = timeParts[0];
    let minutes = timeParts[1];

    // Determine if it's AM or PM
    let ampm = hours >= 12 ? 'PM' : 'AM';

    // Convert hours from 24-hour format to 12-hour format
    hours = hours % 12;
    hours = hours ? hours : 12; // the hour '0' should be '12'

    // Ensure minutes are always two digits
    minutes = minutes < 10 ? '0' + minutes : minutes;

    // Construct the 12-hour format time string
    let strTime = hours + ':' + minutes;

    strTime += ' ' + ampm;

    return strTime;
}

window.formatNumber = function (num) {
    return num.toLocaleString('en-PH', {
        minimumFractionDigits: 2,
        maximumFractionDigits: 2
    });
}
