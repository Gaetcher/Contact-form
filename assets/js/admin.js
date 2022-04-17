import '../styles/admin.scss';

const paginationLinks = document.querySelectorAll('.page-item'),
    messageFeedContainer = document.getElementById('feed-container');
let statusTogglerButtons = document.querySelectorAll('.status-toggler');

const displayFeeds = (data) => {
    messageFeedContainer.innerHTML = data.template;
    statusTogglerButtons = document.querySelectorAll('.status-toggler'); // Update status toggler buttons array

    toggleButtonListenner(); // Refresh the toggle buttons listenner on the new array

    document.getElementById('page-prev').dataset.page = parseInt(data.paginationActive) - 1;
    document.getElementById('page-next').dataset.page = parseInt(data.paginationActive) + 1;
    paginationLinks.forEach(element => {
        element.classList.remove('active')
        if (parseInt(element.dataset.page) === parseInt(data.paginationActive)) {
            element.classList.add('active')
        }
    })
}

// For each pagination link, set up a click listener that will trigger an ajax action to fetch the requested page
const paginationListenner = () => {
    paginationLinks.forEach(element => {
        element.addEventListener('click', event => {
            event.stopPropagation();
            event.preventDefault();

            const ajaxUrl = '/admin/ajax-pagination',
                content = {
                    uToken: uToken,
                    pageRequested: parseInt(element.dataset.page),
                };

            ajaxAction(ajaxUrl, content);
        })
    })
}

const toggleStatus = (data) => {
    const targetElement = document.getElementById('status-' + data.target);
    if (data.status === true) {
        targetElement.innerHTML = `
            <i class="fa-solid fa-check" aria-label="Traité"></i>
        `;
    } else {
        targetElement.innerHTML = `
            <i class="fa-solid fa-xmark" aria-label="À traiter"></i>
        `;
    }
}

// For each message status toggler button, set up a click listener that will trigger an ajax action to toggle the message status
const toggleButtonListenner = () => {
    statusTogglerButtons.forEach(element => {
        element.addEventListener('click', event => {
            event.stopPropagation();
            event.preventDefault();

            const ajaxUrl = '/admin/ajax-message-status-toggler',
                content = {
                    uToken: uToken,
                    target: parseInt(element.dataset.id),
                };

            const htmlContent = `
                <div class="spinner-border" role="status">
                    <span class="visually-hidden">Loading...</span>
                </div>
            `;
            document.getElementById('status-' + content.target).innerHTML = htmlContent;

            ajaxAction(ajaxUrl, content);
        })
    })
}

// Manage Ajax requests with given url and content, and then call responseDispatcher() to handle resuslts
const ajaxAction = (ajaxUrl, content) => {
    fetch(ajaxUrl, {
        method: 'POST',
        headers: {
            'X-Requested-With': 'XMLHttpRequest',
        },
        body: JSON.stringify(content)
    }).then((response) => response.json())
        .then((data) => {
            if (data.error) {
                return
            }
            responseDispatcher(data.content);
        })
        .catch((error) => {
            console.error(error);
        })
}

// Dispatch Ajax response depending on response's code. 
const responseDispatcher = (data) => {
    switch (data.code) {
        case 2001:
            displayFeeds(data);
            break;
        case 2002:
            toggleStatus(data);
            break;
        default:
            break;
    }
}

toggleButtonListenner();
paginationListenner();
