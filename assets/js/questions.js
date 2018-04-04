var $collectionHolder;

// setup an "add a tag" link
var $addAnswerLink = $('<a href="#" class="add_tag_link">Add a tag</a>');
var $newLinkLi = $('<li></li>').append($addAnswerLink);

jQuery(document).ready(function() {
    $collectionHolder = $('ul.answers');

    // add a delete link to all of the existing tag form li elements
    $collectionHolder.find('li').each(function() {
        addAnswerFormDeleteLink($(this));
    });
    // Get the ul that holds the collection of answers
    $collectionHolder = $('ul.answers');

    // add the "add a tag" anchor and li to the answers ul
    $collectionHolder.append($newLinkLi);

    // count the current form inputs we have (e.g. 2), use that as the new
    // index when inserting a new item (e.g. 2)
    $collectionHolder.data('index', $collectionHolder.find(':input').length);

    $addAnswerLink.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // add a new tag form (see next code block)
        $addAnswerForm($collectionHolder, $newLinkLi);
    });
});

function $addAnswerForm($collectionHolder, $newLinkLi) {
    // Get the data-prototype explained earlier
    var prototype = $collectionHolder.data('prototype');

    // get the new index
    var index = $collectionHolder.data('index');

    var newForm = prototype;
    // You need this only if you didn't set 'label' => false in your answers field in TaskType
    // Replace '__name__label__' in the prototype's HTML to
    // instead be a number based on how many items we have
    // newForm = newForm.replace(/__name__label__/g, index);

    // Replace '__name__' in the prototype's HTML to
    // instead be a number based on how many items we have
    newForm = newForm.replace(/__name__/g, index);

    // increase the index with one for the next item
    $collectionHolder.data('index', index + 1);

    // Display the form in the page in an li, before the "Add a tag" link li
    var $newFormLi = $('<li></li>').append(newForm);
    $newLinkLi.before($newFormLi);

    addAnswerFormDeleteLink($newFormLi);
}

function addAnswerFormDeleteLink($answerFormLi) {
    var $removeFormA = $('<a href="#">delete this tag</a>');
    $answerFormLi.append($removeFormA);

    $removeFormA.on('click', function(e) {
        // prevent the link from creating a "#" on the URL
        e.preventDefault();

        // remove the li for the tag form
        $answerFormLi.remove();
    });
}