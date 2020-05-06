// Global Varibles

// inputs
const collection_item_id = document.querySelector('#collection_item_id');
const collection_item_updatedAt = document.querySelector('#collection_item_updatedAt');
const collection_item_picture = document.querySelector('#collection_item_picture');
const collection_item_name = document.querySelector('#collection_item_name');
const collection_item_url = document.querySelector('#collection_item_url');
const user_id = document.getElementById('user_id');

// Modal Input
const collection_item_id_modal_input = document.querySelector('#delete_modal_collection_item_id');
const delete_modal = document.getElementById('delete_modal');
const delete_modal_form = document.getElementById('delete_modal_form');

// HTML Elements
const name_helper_text = document.querySelector("#name-help-text");
const episode_helper_text = document.querySelector("#episode-help-text");

// Array variables
const inputArray = [collection_item_id, collection_item_updatedAt, collection_item_picture, collection_item_name, collection_item_url];
const helperArray = [name_helper_text, episode_helper_text];

// Form variables
const collection_item_form = document.getElementById('collection-item-form');

// API url
const API_URL = "http://manga.ryanwilliamharper.com/api/routes/collectionItem.php";

// Helper functions
const setEmptyInputs = array => array.forEach((item => item.value = ''));

const setEmptyHTMLElements = array => array.forEach((item => item.innerHTML = ''));

const addClasses = (targetElement, ...classes) => {
    const target = document.querySelector(targetElement);
    for(let i = 0; i < classes.length; i++) {
        target.classList.add(...classes[i]);
    }
};

const removeClasses = (targetElement, ...classes) => {
    const target = document.querySelector(targetElement);
    for(let i = 0; i < classes.length; i++) {
        target.classList.remove(...classes[i]);
    }
};

const getUpdatedAt = value => {
    let unixtimestamp = value;
    // convert timestamp to DATETIME
    const months_arr = ['Jan','Feb','Mar','Apr','May','Jun','Jul','Aug','Sep','Oct','Nov','Dec'];
    // Convert timestamp to milliseconds
    const date = new Date(unixtimestamp*1000);
    const year = date.getFullYear();
    const month = months_arr[date.getMonth()];
    const day = date.getDate();
    const convdataTime = `${month}-${day}-${year}`;
    
    return convdataTime;    
};

// set collection item id in the hidden modal
const set_collection_item_id_to_modal = id => collection_item_id_modal_input.value = id;

// Greeting
const greeting = () => {
    const date = new Date();
    const current_hour = date.getHours();
    const welcome_greeting = document.getElementById('welcome-heading');
    console.log(current_hour);

    switch (true) {
        case current_hour <=12:
            welcome_greeting.textContent = 'Good Morning';
            break;
        case current_hour >= 12:
            welcome_greeting.textContent = 'Good Afternoon';
            break;
        case current_hour <= 18 && current_hour >= 18:
            welcome_greeting.textContent = 'Good Evening';
            break; 
        default:
            welcome_greeting.textContent = 'Hi There!';
    }
};

// AniList API
collection_item_form.addEventListener("keyup", e => {

    const url_query = e.target.value;

    // Here we define our query as a multi-line string 
    var query = `
    query($search:String) {
        Media(search:$search, type:ANIME) {  
            id
            updatedAt
            title {
                romaji            
            }     
            coverImage {
                large
            }
            streamingEpisodes {
                url
            }
            externalLinks {
                url
                
            }
            nextAiringEpisode {
                airingAt
            }
        }
    }`;

    // Define our query variables and values that will be used in the query request
    var variables = {
        search: url_query,
    };

    // Define the config we'll need for our Api request
    var url = 'https://graphql.anilist.co',
        options = {
            method: 'POST',
            headers: {
                'Content-Type': 'application/json',
                'Accept': 'application/json',
            },
            body: JSON.stringify({
                query: query,
                variables: variables
            })
        };

    // Make the HTTP Api request
    fetch(url, options).then(handleResponse)
                    .then(handleData)
                    .catch(handleError);

    function handleResponse(response) {
        return response.json().then(function (json) {
            return response.ok ? json : Promise.reject(json);
        });
    }

    function handleData(data) {
        
        removeClasses("#name-help-text", ["text-warning"]);
        addClasses("#name-help-text", ["text-success"]);
        
        removeClasses("#episode-help-text", ["text-warning"]);
        addClasses('#episode-help-text', ["text-success"]);

        document.querySelector('#name-help-text').innerHTML = `Found ${data.data.Media.title.romaji} Successfully!`;
        document.querySelector('#episode-help-text').innerHTML = `There are ${data.data.Media.streamingEpisodes.length} episodes available!`;
        
        // Add values to hidden inputs
        collection_item_id.value = `${data.data.Media.id}`;
        collection_item_picture.value = `${data.data.Media.coverImage.large}`;
        collection_item_updatedAt.value = `${data.data.Media.updatedAt}`;
        collection_item_url.value = `${data.data.Media.streamingEpisodes.shift().url}`;
    };

    function handleError(error) {
        
        removeClasses("#name-help-text", ["text-success"]);
        addClasses("#name-help-text", ["text-warning"]);
        
        removeClasses("#episode-help-text", ["text-success"]);
        addClasses("#episode-help-text", ["text-warning"]);
        
        document.querySelector('#name-help-text').innerHTML = `Error! Can not find ${url_query}!`;      
        document.querySelector('#episode-help-text').innerHTML = '';        
    };
});

//--- End

// GET all collection items
const get_collection_items = () => {
    
    const xhr = new XMLHttpRequest();
    xhr.open("GET", `${API_URL}?user=${user_id.value}`);
    xhr.onload = function() {
        if(this.status === 200) {
            const data = JSON.parse(this.responseText);
            let output = "";
            
            for(var i in data) {
                data.msg == 'none found' ?  output+= '<p class="container">Please add an anime to see here below.</p>' :
                output+=`
                <div class="col-sm-12 col-md-6 col-lg-4 manga-card card" style="border:none;">
                    <div>
                      <div class="row no-gutters">
                        <div class="manga-card-img">
                          <img src="${data[i].collection_item_picture}" class="card-img" alt="${data[i].collection_item_name}">
                        </div>
                        <div class="manga-card-body">
                          <div class="card-body">
                            <h1 class="card-title font-weight-bold">${data[i].collection_item_name}</h1>
                            <p class="card-text">
                                <small class="h5">Last Updated</small><br>
                                <small class="text-muted">${getUpdatedAt(`${data[i].collection_item_updatedAt}`)}</small>
                            </p>
                           <div class="ui-actions">
                                <a target="_blank" href="${data[i].collection_item_url}" class="btn btn-lg btn-custom-blue">Watch Now</a>
                                <a href="#" class="btn btn-lg btn-danger" 
                                    data-toggle="modal" data-target="#delete_modal" onclick="set_collection_item_id_to_modal(${data[i].collection_item_id})">Delete</a>
                            </div> 
                          </div>
                        </div>
                      </div>
                    </div>
                </div>`;
            }
            document.getElementById('manga-page-content').innerHTML = output;

        } else if(this.status === 404) document.getElementById('collection_list_id').innerHTML = 'None Found';
    };
    xhr.send(null);
};

// POST/CREATE a collection item (POST REQUEST)
collection_item_form.addEventListener("submit", e => {
    
    e.preventDefault();

    const data = {
        "collection_item_id":parseInt(collection_item_id.value),
        "collection_item_updatedAt":parseInt(collection_item_updatedAt.value),
        "collection_item_picture":collection_item_picture.value,
        "collection_item_name":collection_item_name.value,
        "collection_item_url": collection_item_url.value,
        "user_id":user_id.value
    };
    params = JSON.stringify(data);

    const xhr = new XMLHttpRequest();
    xhr.open("POST", API_URL);
    xhr.onload = function() {
        if(this.status === 200) {
            const response = JSON.parse(this.responseText);
            document.getElementById('collection-item-form-msg').innerHTML = `<h4 class="mb-5 text-success font-weight-bold">${response.msg}</h4>`;
            setEmptyInputs(inputArray);
            setEmptyHTMLElements(helperArray);

        } else if(this.status === 404) {
            const response = JSON.parse(this.responseText);
            document.getElementById('collection-item-form-msg').innerHTML = `<h4 class="mb-5 text-danger font-weight-bold">${response.msg}</h4>`;
            setEmptyInputs(inputArray);
            setEmptyHTMLElements(helperArray);
        }
    };
    xhr.send(params);

    setTimeout(get_collection_items, 1000);    
});

// DELETE a collection item
delete_modal_form.addEventListener('submit', e => {
    e.preventDefault();

    const data = {};
    data.collection_item_id = parseInt(collection_item_id_modal_input.value);
    data.user_id = user_id.value;  
    const params = JSON.stringify(data);
    
    const xhr = new XMLHttpRequest();
    xhr.open('DELETE', API_URL, true);
    xhr.onload = function() {
        if(this.status === 200) {
            const data = JSON.parse(this.responseText);
            delete_modal.style.display = "none";
            $("#delete_modal").modal('hide');
            document.querySelector('#success_modal .modal-body').innerHTML = `${data.msg}`;
            $("#success_modal").modal('show');
            setTimeout(get_collection_items, 1000);
        }
        else if(this.status === 404) {
            $("#delete_modal").modal('hide');
            document.querySelector('#success_modal .modal-body').innerHTML = `${data.msg}`;
            $("#success_modal").modal('show');
        }
    };
    xhr.send(params);
});

// Init functions
window.addEventListener("DOMContentLoaded", greeting, false);
window.addEventListener('DOMContentLoaded', get_collection_items, false);