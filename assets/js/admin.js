const paginationLinks = document.querySelectorAll('.page-item');
const messageFeedContainer = document.getElementById('feed-container');

const displayFeeds = (data) => {
    messageFeedContainer.innerHTML = data.template;
    document.getElementById('page-prev').dataset.page = parseInt(data.paginationActive) - 1
    document.getElementById('page-next').dataset.page = parseInt(data.paginationActive) + 1
    paginationLinks.forEach(element => {
        element.classList.remove('active')
        if(parseInt(element.dataset.page) === parseInt(data.paginationActive)) {
          element.classList.add('active')  
        }
    })
}

const getMessageFeeds = (pageLink) => {
    const ajaxUrl = '/admin/ajax-pagination',
        content = {
            uToken: uToken,
            pageRequested: parseInt(pageLink.dataset.page),
        };
        
    fetch(ajaxUrl, {
        method: 'POST',
        headers: {
            'X-Requested-With' : 'XMLHttpRequest',
        },
        body: JSON.stringify(content)
    }).then((response) => response.json())
        .then((data) => {
            displayFeeds(data.content)
        })
        .catch((error) => {
            console.error(error)
        })
}

paginationLinks.forEach(element => {
    element.addEventListener('click', event => {
        event.preventDefault();

        getMessageFeeds(element);
    })
})