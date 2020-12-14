
/*----------------------------------------------------*/
/*  Ratings Script
/*----------------------------------------------------*/

/*  Numerical Script
/*--------------------------*/
function numericalRating(ratingElem) {

    $(ratingElem).each(function() {
        var dataRating = $(this).attr('data-rating');

        // Rules
        if (dataRating >= 4.0) {
            $(this).addClass('high');
        } else if (dataRating >= 3.0) {
            $(this).addClass('mid');
        } else if (dataRating < 3.0) {
            $(this).addClass('low');
        }

    });

} numericalRating('.numerical-rating');


/*  Star Rating
/*--------------------------*/
function starRating(ratingElem) {

    $(ratingElem).each(function() {

        var dataRating = $(this).attr('data-rating');

        // Rating Stars Output
        function starsOutput(firstStar, secondStar, thirdStar, fourthStar, fifthStar) {
            return(''+
                '<span class="'+firstStar+'"></span>'+
                '<span class="'+secondStar+'"></span>'+
                '<span class="'+thirdStar+'"></span>'+
                '<span class="'+fourthStar+'"></span>'+
                '<span class="'+fifthStar+'"></span>');
        }

        var fiveStars = starsOutput('star','star','star','star','star');

        var fourHalfStars = starsOutput('star','star','star','star','star half');
        var fourStars = starsOutput('star','star','star','star','star empty');

        var threeHalfStars = starsOutput('star','star','star','star half','star empty');
        var threeStars = starsOutput('star','star','star','star empty','star empty');

        var twoHalfStars = starsOutput('star','star','star half','star empty','star empty');
        var twoStars = starsOutput('star','star','star empty','star empty','star empty');

        var oneHalfStar = starsOutput('star','star half','star empty','star empty','star empty');
        var oneStar = starsOutput('star','star empty','star empty','star empty','star empty');

        // Rules
        if (dataRating >= 4.75) {
            $(this).append(fiveStars);
        } else if (dataRating >= 4.25) {
            $(this).append(fourHalfStars);
        } else if (dataRating >= 3.75) {
            $(this).append(fourStars);
        } else if (dataRating >= 3.25) {
            $(this).append(threeHalfStars);
        } else if (dataRating >= 2.75) {
            $(this).append(threeStars);
        } else if (dataRating >= 2.25) {
            $(this).append(twoHalfStars);
        } else if (dataRating >= 1.75) {
            $(this).append(twoStars);
        } else if (dataRating >= 1.25) {
            $(this).append(oneHalfStar);
        } else if (dataRating < 1.25) {
            $(this).append(oneStar);
        }

    });

} starRating('.star-rating');
