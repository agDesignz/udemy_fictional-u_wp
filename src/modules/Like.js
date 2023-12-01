import $ from 'jquery';

class Like {
  constructor() {
    this.events(); // So the event listeners get added as soon as the page loads.
  }

  events() {
    document.querySelector(".like-box").addEventListener("click", this.ourClickDispatcher.bind(this));
  }

  //methods
  ourClickDispatcher(e) {
    let currentLikeBox = e.target.closest('.like-box');
    // Only job: should we add or delete a like
    if (currentLikeBox.getAttribute("data-exists") == 'yes') {
      this.deleteLike(currentLikeBox);
    } else {
      this.createLike(currentLikeBox);
    }
  }

  createLike(currentLikeBox) {
    $.ajax({
      url: universityData.root_url + '/wp-json/university/v1/manageLike',
      type: 'POST',
      data: {'professorId': currentLikeBox.data('professor')},
      success: (response) => {
        console.log(response);
      },
      error: (response) => {
        console.log(response);
      }
    })
  }

  deleteLike() {
    $.ajax({
      url: universityData.root_url + '/wp-json/university/v1/manageLike',
      type: 'DELETE',
      success: (response) => {
        console.log(response);
      },
      error: (response) => {
        console.log(response);
      }
    })
  }
}

export default Like;
