import $ from 'jquery';

class Search {
  // 1. Constructor: where we describe and initiate our object
  constructor() {
    this.addSearchHTML();
    this.resultsDiv = $("#search-overlay__results");
    this.openButton = $(".js-search-trigger");
    this.closeButton = $(".search-overlay__close");
    this.searchOverlay = $(".search-overlay");
    this.searchField = $("#search-term")
    this.events();
    this.isOverlayOpen = false;
    this.isSpinnerVisible = false;
    this.previousValue;
    this.typingTimer;
  }

  // 2. List events (eg: click)
  events() {
    this.openButton.on('click', this.openOverlay.bind(this));
    this.closeButton.on('click', this.closeOverlay.bind(this));
    $(document).on('keydown', this.keyPressDispatcher.bind(this));
    this.searchField.on('keyup', this.typingLogic.bind(this));
  }
  // 3. Methods
  typingLogic() {

    if (this.searchField.val() != this.previousValue) {
      clearTimeout(this.typingTimer);

      if (this.searchField.val()) {
        if (!this.isSpinnerVisible) {
          this.resultsDiv.html('<div class="spinner-loader"></div>');
          this.isSpinnerVisible = true;
        }
        this.typingTimer = setTimeout(this.getResults.bind(this), 750);
      } else {
        this.resultsDiv.html('');
        this.isSpinnerVisible = false;
      }
    }

    this.previousValue = this.searchField.val();
  }

  getResults() {
    $.getJSON(universityData.root_url + '/wp-json/university/v1/search?term=' + this.searchField.val(), (results) => {
      this.resultsDiv.html(`
          <div class="row">
            <div class="one-third">
              <h2 class="search-overlay__section-title">General Information</h2>
              ${results.generalInfo.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
                ${results.generalInfo.map(item => `<li><a href="${item.link}">${item.title}</a> ${item.postType == 'post' ? `by ${item.author}` : ''}</li>`).join('')}
              ${results.generalInfo.length ? '</ul>' : ''}
            </div>
            <div class="one-third">
                <h2 class="search-overlay__section-title">Programs</h2>
                ${results.programs.length ? '<ul class="link-list min-list">' : `<p>No programs match that search. <a href="${universityData.root_url}">View all programs</a></p>`}
                  ${results.programs.map(item => `<li><a href="${item.link}">${item.title}</a></li>`).join('')}
                ${results.programs.length ? '</ul>' : ''}

                <h2 class="search-overlay__section-title">Professors</h2>
                ${results.professors.length ? '<ul class="link-list min-list">' : `<p>No professors match that search. <a href="${universityData.root_url}">View all campuses</a></p>`}
                  ${results.professors.map(item => `<li><a href="${item.link}">${item.title}</a></li>`).join('')}
                ${results.professors.length ? '</ul>' : ''}

            </div>
            <div class="one-third">
              <h2 class="search-overlay__section-title">Campuses</h2>

              <h2 class="search-overlay__section-title">Events</h2>

            </div>
          </div>
      `);
      this.isSpinnerVisible = false;
    });

    // Delete this code later
    // $.when(
    //   $.getJSON(universityData.root_url + '/wp-json/wp/v2/posts?search=' + this.searchField.val()),
    //   $.getJSON(universityData.root_url + '/wp-json/wp/v2/pages?search=' + this.searchField.val())
    // ).then((posts, pages) => {
    //     var combinedResults = posts[0].concat(pages[0]); // because the results come back with more than just json
    //     this.resultsDiv.html(`
    //     <h2 class="search-overlay__section-title">General Information</h2>
    //     ${combinedResults.length ? '<ul class="link-list min-list">' : '<p>No general information matches that search.</p>'}
    //       ${combinedResults.map(item => `<li><a href="${item.link}">${item.title.rendered}</a> ${item.type == 'post' ? `by ${item.authorName}` : ''}</li>`).join('')}
    //     ${combinedResults.length ? '</ul>' : ''}
    //   `);
    //     this.isSpinnerVisible = false;
    //   },
    //   () => {
    //     this.resultsDiv.html('<p>Unexpected error, please try again</p>');
    //   }
    // );
  }

  keyPressDispatcher(e) {
    if (e.key == 's' && !this.isOverlayOpen && !$('input, textarea').is(':focus')) {
      this.openOverlay();
    }
    if (e.key == 'Escape' && this.isOverlayOpen) {
      this.closeOverlay();
    }
  }

  openOverlay() {
    this.searchOverlay.addClass('search-overlay--active');
    $("body").addClass("body-no-scroll");
    this.searchField.val('');
    setTimeout(() => this.searchField.focus(), 301);
    this.isOverlayOpen = true;
    return false;
  }

  closeOverlay() {
    this.searchOverlay.removeClass('search-overlay--active');
    $("body").removeClass("body-no-scroll");
    this.isOverlayOpen = false;
  }

  addSearchHTML() {
    $("body").append(`
      <div class="search-overlay">
        <div class="search-overlay__top">
          <div class="container">
            <i class="fa fa-search search-overlay__icon" aria-hidden="true"></i>
            <input type="text" class="search-term" placeholder="What are you looking for?" id="search-term" autocomplete="off">
            <i class="fa fa-window-close search-overlay__close" aria-hidden="true"></i>
          </div>
        </div>
        <div class="container">
          <div id="search-overlay__results">

          </div>
        </div>
      </div>
    `);
  }

}

export default Search;
