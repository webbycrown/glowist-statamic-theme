jQuery(document).ready(function () {

  // ===== Sidebar Menu Js ===== //

   jQuery('.nav-item > .nav-link').click(function (e) {
    let parentItem = jQuery(this).closest('.nav-item');

    // If it has submenu, toggle it
    if (parentItem.hasClass('has-submenu')) {
      e.preventDefault(); // prevent navigating
      let subMenu = parentItem.find('.sub-menu').first();

      // Toggle current submenu
      subMenu.slideToggle();

      // Close other submenus
      jQuery('.nav-item.has-submenu').not(parentItem).find('.sub-menu').slideUp();

      // Toggle active class
      parentItem.toggleClass('active');
      jQuery('.nav-item.has-submenu').not(parentItem).removeClass('active');
    }
  });

  // On load, show submenu if a child is active
   jQuery(".sub-menu-ul .active").each(function () {
    let parentNavItem = jQuery(this).closest(".nav-item.has-submenu");
    if (parentNavItem.length) {
      parentNavItem.addClass("active");
      parentNavItem.find(".sub-menu").first().css("display", "block");
    }
  });

  // ===== Topic Tabs Js ===== //

  jQuery(".topic-tabs").on("click", ".topic-tabs-link ", function (e) {
    e.preventDefault();
    jQuery(".topic-tabs-link").removeClass("active");
    jQuery(".topic-content").removeClass("show");
    jQuery(this).addClass("active");
    jQuery(".topic-content").addClass("hidden");
    jQuery(jQuery(this).attr("href")).addClass("show").removeClass("hidden");
  });

  // ===== Post More Less Js ===== //

  jQuery(document).on('click','.more-less' ,function(){

    var paragraph = jQuery(this).closest('.post-paragraph'); // Get the specific post-paragraph

    // Toggle active class for the specific post-paragraph
    paragraph.toggleClass('active');

    // Toggle the 'line-clamp-2' class on the content div inside this specific paragraph
    paragraph.find('> div').toggleClass('line-clamp-2');

    // Toggle the text of the clicked 'more-less' button
    var buttonText = jQuery(this).text() === '(more)' ? '(less)' : '(more)';
    jQuery(this).text(buttonText);
});

  // ===== Sidebar Toggle Js ===== //

  jQuery(".menu-toggle").click(function () {
    jQuery(".sidebar").toggleClass("show");
  });

  // ===== Mobile Search Toggle Js ===== //

  jQuery(".search-ico").click(function () {
    jQuery(".mobile-search").addClass("show");
  });
  jQuery(".search-close").click(function () {
    jQuery(".mobile-search").removeClass("show");
  });

  // ===== Post Swiper Slider Js ===== //
  function postSliderWrapper (){
    if (jQuery(".post-slider .post-slider-wrapper").length > 0) {
        var swiper = new Swiper(".post-slider .post-slider-wrapper", {
            navigation: {
                nextEl: ".post-slider-wrapper .swiper-button-next",
                prevEl: ".post-slider-wrapper .swiper-button-prev",
            },
            pagination: {
                el: ".post-slider-wrapper .swiper-pagination",
            },
        });
    }
}

  // ===== Notification-dropwdown js ===== //

  jQuery(document).on('click', ".notification-ico", function () {
    jQuery(".notification-dropdown-popup").removeClass("hidden")
  });
  jQuery(document).mouseup(function (e) {
    var container = jQuery(".notification-dropdown-popup");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      container.addClass("hidden");
    }
  });

  // ===== Notification Tabs Js ===== //

  jQuery('.tab-btn').click(function () {
    // Remove active class from all buttons
    jQuery('.tab-btn').removeClass('active border-b-2 border-gray-900 text-gray-800');
    // Add active class to clicked button
    jQuery(this).addClass('active border-b-2 border-gray-900 text-gray-900');

    // Hide all tab panes
    jQuery('.tab-pane').addClass('hidden');

    // Show the clicked tab's content
    var index = jQuery(this).index();
    jQuery('.tab-pane').eq(index).removeClass('hidden');
  });


  // ===== User-dropwdown js ===== //

  jQuery(document).on('click', ".user-dropdown > a", function () {
    jQuery(".user-dropdown-popup").removeClass("hidden")
  });
  jQuery(document).mouseup(function (e) {
    var container = jQuery(".user-dropdown-popup");
    if (!container.is(e.target) && container.has(e.target).length === 0) {
      container.addClass("hidden");
    }
  });

  // ===== Avatar js ===== //

  jQuery(document).ready(function () {
    var readURL = function (input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          jQuery(".profile-pic").attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
      }
    };

    jQuery(".file-upload").on("change", function () {
      readURL(this);
    });

    jQuery(".upload-button").on("click", function () {
      jQuery(".file-upload").click();
    });
  });

  // ===== Cover js ===== //

  jQuery(document).ready(function () {
    var readURL = function (input) {
      if (input.files && input.files[0]) {
        var reader = new FileReader();

        reader.onload = function (e) {
          jQuery(".cover-photo").attr("src", e.target.result);
        };

        reader.readAsDataURL(input.files[0]);
      }
    };

    jQuery(".cover-upload").on("change", function () {
      readURL(this);
    });

    jQuery(".upload-button").on("click", function () {
      jQuery(".cover-upload").click();
    });
  });

  // ===== Social Share Siide js ===== // 

  jQuery(document).on("click",".post #toggleSocial1", function () {
    jQuery( this ).parents('.post').find( '#SocialOption' ).slideToggle();
  });
  
  // ===== Reply js ===== // 

jQuery(".comment-item .reply-now").on("click", function () {
    var commentItem = jQuery(this).closest(".comment-item");

    // Toggle the class on the .comment-item element
    commentItem.toggleClass("active");

    // Check if the class is active
    if (commentItem.hasClass("active")) {

        var postId = jQuery(this).data("post_id");
        var parentId = jQuery(this).data("parent_id");
        var authorId = jQuery(this).data("author_id");

        // Add the HTML first, but hide it initially
        commentItem.append(`<form class="reply-form reply-forms bg-gray-100 p-4 rounded mb-4">\
            <input type="hidden" name="post_id" value="${postId}">\
            <input type="hidden" name="parent_id" value="${parentId}">\
            <input type="hidden" name="author_id" value="${authorId}">\
            <textarea name="comment" class="w-full p-2 border rounded mb-2" placeholder="Write your reply..." required></textarea>\
            <button type="submit" class="btn-primary px-4 py-2 rounded bg-blue-600 text-white">Send</button>\
            </form>`);
        // Then slide it down to show
        commentItem.find(".reply-forms").hide().slideDown();
    } else {
        // Slide up the reply-box and remove it after the animation completes
        commentItem.find(".reply-forms").slideUp(function() {
            jQuery(this).remove();
        });
    }
  });
jQuery.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        }
    });

  // Handle form submission (works even for dynamically added forms)
jQuery(document).on("submit", ".reply-forms", function (e) {
    e.preventDefault();

    var form = jQuery(this);
    var formData = {
       post_id: form.find('[name="post_id"]').val(),
       parent_id: form.find('[name="parent_id"]').val(),
       author_id: form.find('[name="author_id"]').val(),
       comment: form.find('[name="comment"]').val().trim()
    };

  // Simple validation
    if (!formData.comment) {
      // alert("All fields are required.");
      return;
    } // Send to backend
    jQuery.ajax({
    url: '/comments/store',  // Laravel route
    type: 'POST',
    data: formData,
    headers: {
            'X-CSRF-TOKEN': jQuery('meta[name="csrf-token"]').attr('content')
        },
    success: function(response) {
      form[0].reset(); // Reset form
      location.reload(true);
    },
    error: function(xhr) {
      // alert("Failed to submit comment.");
    }
  });
});


  // ===== Follow Following js ===== //


const $buttons = $(".btn-small");
    const authorId = $("#author_id").val();
    const userId = $("#post_id").val();
    const $followerCount = $("#follower-count");

    function updateFollowerCount(count) {
        $followerCount.text(count + " Followers");
    }

    // Setup CSRF token for Laravel
    $.ajaxSetup({
        headers: {
            'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content')
        }
    });

    // Handle follow/unfollow button click
    $(document).on("click",".btn-small" ,function () {
        const $button = $(this);
        const userId = $button.data("user_id");
        const authorId = $button.data("author_id");
        const isFollowing = $button.hasClass("following");
        const action = isFollowing ? "unfollow" : "follow";
        const route = action === "follow" ? "/authors/follow" : "/authors/unfollow";

        // Toggle the button state immediately for better UX
        $button.toggleClass("following").text(isFollowing ? "Follow" : "Following");

        // Send the AJAX request
        $.ajax({
            url: route,
            method: "POST",
            data: {
                author_id: authorId,
                user_id: userId
            },
            dataType: "json",
            success: function (data) {
                if (data.success) {
                    updateFollowerCount(data.followers);
                } else {
                    // alert(data.error || "Something went wrong. Please try again.");
                    // Revert the button state on error
                    $button.toggleClass("following").text(isFollowing ? "Following" : "Follow");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
                // Revert the button state on error
                $button.toggleClass("following").text(isFollowing ? "Following" : "Follow");
            }
        });
    });

    $(document).on("click", "#like-btn", function () {
        const $button = $(this);
        const blog_id = $button.attr('data-blog_id');
        const author_id = $button.attr('data-author_id');
        const route = "/store/like";

        $.ajax({
            url: route,
            method: "POST",
            data: { author_id, blog_id },
            dataType: "json",
            success: function (data) {
                if (data.status === true) {

                    let $parent = $button.closest('.action-result');

                    if ($parent.length === 0) {
                        $parent = $(`.action-result[data-blog_id="${blog_id}"]`);
                    }

                    const $likeCountEl =  $parent.find('#like-count');
                    let likeCount = parseLikeCount($likeCountEl.text());

                    if (data.message.includes('Liked')) {
                        likeCount += 1;
                    }
                    $likeCountEl.text(formatLikeCount(likeCount));
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });

    $(document).on("click", "#unlike-btn", function () {
        const $button = $(this);
        const blog_id = $button.attr('data-blog_id');
        const author_id = $button.attr('data-author_id');
        const route = "/store/unlike";

        $.ajax({
            url: route,
            method: "POST",
            data: { author_id, blog_id },
            dataType: "json",
            success: function (data) {
                if (data.status === true) {
                    let $parent = $button.closest('.action-result');

                    if ($parent.length === 0) {
                        $parent = $(`.action-result[data-blog_id="${blog_id}"]`);
                    }

                    const $likeCountEl =  $parent.find('#like-count');
                    let likeCount = parseLikeCount($likeCountEl.text());

                    if (data.message.includes('Liked')) {
                        likeCount += 1;
                    } else if (data.message.includes('Unliked')) {
                        likeCount = Math.max(0, likeCount - 1);
                    }

                    $likeCountEl.text(formatLikeCount(likeCount));
                }
            },
            error: function (xhr, status, error) {
                console.error("Error:", error);
            }
        });
    });

    function formatLikeCount(number) {
        if (number >= 1000) {
            return (number / 1000).toFixed(1).replace(/\.0$/, '') + 'k';
        }
        return number.toString();
    }

    function parseLikeCount(text) {
        text = text.trim().toLowerCase();
        if (text.includes('k')) {
            return Math.round(parseFloat(text) * 1000);
        }
        const parsed = parseInt(text, 10);
        return isNaN(parsed) ? 0 : parsed;
    }

    $(document).on('click','.copy-link-btn', function () {
        const link = $(this).data('url');

            // Copy the link to the clipboard
        const tempInput = $('<input>');
        $('body').append(tempInput);
        tempInput.val(link).select();
        document.execCommand('copy');
        tempInput.remove();

    });

    function formatShareCount(count) {
        count = parseInt(count, 10);
        if (isNaN(count)) return '0 Shares';

        if (count >= 1000) {
            let formatted = (count / 1000).toFixed(1).replace(/\.0$/, '');
            return formatted + 'k Shares';
        }
        return count + (count === 1 ? ' Share' : ' Shares');
    }

    $(document).on("click", ".social-share-btn", function () {
        const $button = $(this);
        const blog_id = $button.data('blog_id');
        const author_id = $button.data('author_id');
        const route = "/social-share";

        $.ajax({
            url: route,
            method: "get",
            data: { author_id, blog_id },
            dataType: "json",
            success: function (data) {
                if (data.status === true) {
                    let $parent = $button.closest('.action-result');

                    if ($parent.length === 0) {
                        $parent = $(`.action-result[data-blog_id="${blog_id}"]`);
                    }

                    const $shareCountEl = $parent.find('.total_share_count');

                    if ($shareCountEl.length === 0) {
                        console.error("Share count element not found!");
                        return;
                    }

                    let currentText = $shareCountEl.text().trim();

                    let count = 0;
                    const match = currentText.match(/[\d.]+/);
                    if (match) {
                        if (currentText.toLowerCase().includes('k')) {
                            count = parseFloat(match[0]) * 1000;
                        } else {
                            count = parseInt(match[0]);
                        }
                    }

                    count = isNaN(count) ? 1 : count + 1;

                    $shareCountEl.text(formatShareCount(count));
                } else {
                    console.warn(data.message || "Share count update failed");
                }
            },
            error: function (xhr, status, error) {
                console.error("Error on AJAX:", error);
            }
        });
    });


    $(document).on('click','.comment-like',function() {
        var id = $(this).data('id');

        $.post('/comments/' + id + '/like', function(data) {
            $('.like-count[data-id="' + id + '"]').text(data.likes + ' Like');
        });
    });

    $(document).on('click','.comment-unlike',function() {
        var id = $(this).data('id');
        $.post('/comments/' + id + '/unlike', function(data) {
            $('.like-count[data-id="' + id + '"]').text(data.likes + ' Like');
        });
    });   

    $(document).on('click','.comment-delete',function() {
        var commentId = $(this).data('id');
        $.ajax({
            url: '/comments/delete',
            type: 'DELETE',
            data:{
                id:commentId
            },
            headers: {
                'X-CSRF-TOKEN': $('meta[name="csrf-token"]').attr('content') // for web.php
            },
            success: function(response) {
                toastr.success(response.message);
                // Optionally remove the comment from the DOM
                $('#comment-' + commentId).remove();
            },
            error: function(xhr) {
                alert('Error deleting comment.');
            }
        });
    });



    postSliderWrapper();

    function loadAuthorFollowButtons(authorId) {
        return $.get('/partials/author-follow-buttons', { author_id: authorId });
    }
    function loadAuthorSocialOptionButtons(authorId,blog_id) {
        return $.get('/partials/social-option', { author_id: authorId ,id: blog_id });
    }

    //home page jquery
    let nextPage = 1;
    let lastPage = null;
    let isLoading = false;
    const loadedPostIdsByTab = {};

    function renderPost(post,followButtonsHtml,socialHtml) { 
       let assets_field = '';
        if (post.assets_field && post.assets_field.length > 0) {
            const isVideo = post.show_videos === true;
            const firstAsset = post.assets_field[0]; 
            if (post.assets_field.length === 1) {
                assets_field = `<div>`;
                if (isVideo) {
                    assets_field += `
                        <a  href="/blog/${post.slug}">
                            <video width="710" height="440" controls class="w-full">
                                <source src="${firstAsset}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                        </a>`;
                } else {
                    assets_field += `
                        <a href="/blog/${post.slug}">
                            <img width="710" height="440" src="${firstAsset}" alt="Image" class="w-full">
                        </a>`;
                }
                assets_field += `</div>`;
            } else if (post.assets_field.length > 1) {
                assets_field = `
               <div class="post-slider">
                    <div class="swiper post-slider-wrapper swiper-initialized swiper-horizontal swiper-backface-hidden">
                        <div class="swiper-wrapper" id="swiper-wrapper-2cc85bede9aa7b85" aria-live="polite">`;

                post.assets_field.forEach((url, index) => {
                    if (url) {
                        assets_field += `<div class="swiper-slide swiper-slide-active" role="group" aria-label="${index + 1} / ${post.assets_field.length}" style="width: 710px;">
                            <a href="javascript:;" class="flex w-full">
                                <img width="710px" height="440px" src="${url}" alt="Image ${index + 1}" class="w-full">
                            </a>
                        </div>`;
                    }
                });

                assets_field += `</div>
                        <div class="swiper-button-next" tabindex="0" role="button" aria-label="Next slide" aria-controls="swiper-wrapper-2cc85bede9aa7b85" aria-disabled="false"></div>
                        <div class="swiper-button-prev swiper-button-disabled" tabindex="-1" role="button" aria-label="Previous slide" aria-controls="swiper-wrapper-2cc85bede9aa7b85" aria-disabled="true"></div>
                        <div class="swiper-pagination-dots">
                            <div class="swiper-pagination swiper-pagination-bullets swiper-pagination-horizontal"><span class="swiper-pagination-bullet swiper-pagination-bullet-active" aria-current="true"></span><span class="swiper-pagination-bullet"></span><span class="swiper-pagination-bullet"></span></div>
                        </div>
                        <span class="swiper-notification" aria-live="assertive" aria-atomic="true"></span></div>
                    </div>`;
            }
        }

        return `
        <div class="post sm:mb-6 mb-4 home_blog_tab1" data-id="${post.id}">
            <div class="flex items-center justify-between mb-2">
                <div class="flex items-center">
                    <div class="relative">
                        <a href="/authors/${post.author.slug}" class="flex 2sm:w-[30px] 2sm:h-[30px] w-[28px] h-[28px] rounded-full overflow-hidden me-2">
                            <img height="30" width="30" src="${post.author.avatar || ''}" alt="${post.author.name || ''}" class="w-full h-full">
                        </a>
                    </div>
                    <div class="2sm:flex block items-center">
                        <p class="font-matter sm:text-base text-sm font-medium text-gray-900 hover:text-gray-800">
                            <a href="/authors/${post.author.slug}" class="flex">${post.author.name}</a>
                        </p>
                        <p class="date relative font-matter sm:text-base 2sm:text-sm text-xs text-gray-800 2sm:ps-4">
                            ${timeAgo(post.create_date)}
                        </p>
                    </div>
                </div>
             <div class="flex items-center">
                    ${followButtonsHtml}
                </div>
            </div>
            <div class="2sm:mb-4 mb-3">
            <h2 class="font-matter text-gray-900 hover:text-gray-800 font-semibold sm:text-xl 2sm:text-lg text-base mb-[6px]"><a href="/blog/${post.slug}">${post.title}</a></h2>
            <div class="post-paragraph relative">
            <div class="line-clamp-2 font-matter 2sm:text-base text-sm text-gray-800">
            <p class="mb-2 description-text">${post.description}</p>
            </div>
            <span class="more-less cursor-pointer">(more)</span>
            </div>
            </div>
            ${assets_field}
            ${socialHtml}
            </div>`;
    }

    function timeAgo(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diffTime = now - date;
        const diffDays = Math.floor(diffTime / (1000 * 60 * 60 * 24));
        if (diffDays === 0) return 'Today';
        if (diffDays === 1) return 'Yesterday';
        return `${diffDays} days ago`;
    }

    $(document).on('click','.topic-tabs-link',function(){
        nextPage = 1;
        loadMorePosts();
    });
    
    function getActiveTabInfo() {
        const $activeTab = $(".topic-tabs-link.active");
        return {
            id: $activeTab.attr("href")?.replace("#", ""),  // e.g. "tabs1"
            type: $activeTab.data("tab-type"),              // e.g. "for-you"
        };
    }

    let currentCategory = null;

    function getLastUrlSegment() {
         return window.location.pathname.split('/').filter(Boolean).pop() || null;
    }

    function loadMorePosts() {

        if (isLoading) return;
        if (lastPage !== null && nextPage > lastPage) return;
        if (!nextPage) return;

        isLoading = true;

        const { id: activeTabId, type: tabTypeRaw } = getActiveTabInfo();
        const tabType = tabTypeRaw || null;
        let ActiveTabId = activeTabId ;
        const $activeContainer = ActiveTabId != null ? $("#" + ActiveTabId) : '';
        currentCategory = getLastUrlSegment();

        if (currentCategory != null) {
            ActiveTabId = 'blog-category'; // ✅ Now valid 
        }

        if (!loadedPostIdsByTab[ActiveTabId]) {
            loadedPostIdsByTab[ActiveTabId] = new Set();
        }
        const loadedPostIds = loadedPostIdsByTab[ActiveTabId];

        $.ajax({
            url: '/blog-posts',
            type: 'GET',
            data: { page: nextPage, limit: 2, tab: tabType, category: currentCategory},
            dataType: 'json',
            success: function (response) {
                const posts = response.data || [];
                if( ActiveTabId == 'blog-category' ){
                    const formattedCategory = currentCategory.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');  
                    const $header = $('#' + ActiveTabId).closest('.lg\\:max-w-\\[710px\\]').find('h2.category_name').text(formattedCategory);
                }
                if (posts.length === 0) {
                    lastPage = nextPage - 1;
                    $("#" + ActiveTabId).append('<p style="text-align: center;">Blog not found</p>');
                    return;
                }else{
                    posts.forEach(post => {
                        if (!loadedPostIds.has(post.id)) {
                            $.when(
                                loadAuthorFollowButtons(post.author.author_id),
                                loadAuthorSocialOptionButtons(post.author.author_id,post.id)
                                ).done(function(followButtonsHtml, socialButtonsHtml) {
                                    const followHtml = followButtonsHtml[0];
                                    const socialHtml = socialButtonsHtml[0];
                                    const postHtml = renderPost(post, followHtml,socialHtml);

                                    $("#" + ActiveTabId).append(postHtml);
                                    loadedPostIds.add(post.id);
                                    postSliderWrapper();
                                });
                            }
                        });
                }

                nextPage = response.meta.next_page;
                lastPage = response.meta.last_page;
            },
            error: function () {
                console.error('Error loading posts');
            },
            complete: function () {
                isLoading = false;
            }
        });
    }

    // Load 2 posts immediately on page load
    loadMorePosts();

    // Load more posts when user scrolls near bottom
    $(window).on('scroll', function () {
        if ($(window).scrollTop() + $(window).height() >= $(document).height() - 150) {
            loadMorePosts();
        }
    });
    //home page jquery end

    function renderSearchResults(groupedResults) {
        const $resultsContainer = $('.searchResults');
        $resultsContainer.empty();

        const hasResults = Object.keys(groupedResults).length > 0;
        if (!hasResults) {
            $resultsContainer.html('<p class="text-gray-500 p-2">No results found.</p>');
            return;
        }
        $.each(groupedResults, function (category, items) {
    
            const formattedCategory = category.split('-').map(word => word.charAt(0).toUpperCase() + word.slice(1)).join(' ');  

            const categoryHeader = `<p class="flex items-center font-matter 2xl:text-base text-sm text-gray-800 2xl:mb-3 xl:mb-2 mb-3">
                                       
                                        ${formattedCategory}
                                    </p> 
            <ul></ul>`;
            
            const $categoryBlock = $(categoryHeader);
            const $ul = $categoryBlock.filter('ul').length ? $categoryBlock.filter('ul') : $categoryBlock.find('ul');
            items.forEach(item => {
                let assets_field = '';

                const fullText = item.description.trim();
                const shortText = fullText.length > 50 ? fullText.substring(0, 50) + '...' : fullText;

                if( item.show_videos === true ){
                    assets_field += `<a href="${item.url}" class="flex w-full 2xl:max-w-[100px] 2xl:min-w-[100px] 2xl:h-[80px] xl:max-w-[60px] xl:min-w-[60px] xl:h-[50px] 2sm:max-w-[100px] 2sm:min-w-[100px] 2sm:h-[80px] max-w-[80px] min-w-[80px] h-[70px] me-3">
                            <video width="100" height="80" controls class="w-full h-full object-cover">
                                <source src="${item.assets_field}" type="video/mp4">
                                Your browser does not support the video tag.
                            </video>
                    </a>`;
                }else{
                    assets_field +=` <a href="${item.url}" class="flex w-full 2xl:max-w-[100px] 2xl:min-w-[100px] 2xl:h-[80px] xl:max-w-[60px] xl:min-w-[60px] xl:h-[50px] 2sm:max-w-[100px] 2sm:min-w-[100px] 2sm:h-[80px] max-w-[80px] min-w-[80px] h-[70px] me-3">
                          <img width="100" height="80" src="${item.assets_field}" alt="${item.title}" class="w-full h-full object-cover">
                    </a>`;
                }
                const listItem = `
                    <li>
                      <div class="flex items-center 2xl:pb-4 xl:pb-3 2sm:pb-4 pb-3 2xl:mb-4 xl:mb-3 2sm:mb-4 mb-3 border-b border-gray-400">
                       ${assets_field}
                        <div>
                          <h4 class="font-matter 2xl:text-base xl:text-sm 2sm:text-base text-sm text-gray-900 hover:text-gray-800 font-medium 2xl:mb-1 xl:mb-0 mb-1">
                            <a href="${item.url}" class="flex">${item.title}</a>
                          </h4>
                          <p class="line-clamp-1 font-matter 2xl:text-sm lg:text-xs 2sm:text-sm text-xs text-gray-800 2xl:mb-2 mb-1">${shortText}</p>
                          <div class="flex items-center">

                            <a href="javascript:;" class="flex w-[16px] h-[16px] rounded-full overflow-hidden me-[5px]">
                              <img height="18" width="18" src="${item.author.avatar}" alt="${item.author.name}" class="w-full h-full">
                            </a>
                            <div class="flex items-center">
                              <p class="font-matter text-xs text-gray-900 hover:text-gray-800">
                                <a href="javascript:;" class="flex">${item.author.name}</a>
                              </p>
                              <p class="relative font-matter text-xs text-gray-800 ps-[8px]">${ item.date}</p>
                            </div>
                          </div>
                        </div>
                      </div>
                    </li>`;

                    $ul.append(listItem);
            });
                     $resultsContainer.append($categoryBlock);
        });
    }


    $(document).on('click input','#searchInput',function(e){
        e.preventDefault();
        const query = $(this).val().trim();
        performSearch(query);
    });

    if($('#searchInput').val() == ''){
        $('.searchResults').addClass('hidden');
    }else{

        $('.searchResults').removeClass('hidden');
    }

    function performSearch(query,type = '') {
        var limit = (type == 'sidebar') ? 4 : 0;
        $.ajax({
            url: '/comments/search',
            type: 'GET',
            data: { query: query , limit: limit },
            dataType: 'json',
            success: function (response) {
               
                if( type == 'sidebar' ){
                    renderSidebarSearchResults(response.results)
                }else{
                   renderSearchResults(response.results);
                   $('.searchResults').removeClass('hidden');
               }
            },
            error: function() {
                $('.searchResults ul').html('<li><p class="text-red-500 p-2">Search failed, please try again.</p></li>');
                $('.searchResults').removeClass('hidden');
            }
        });
    }

    $(document).on('click', function (e) {
        const $popup = $('.searchResults');
        const $input = $('#searchInput');

        // If the click is outside both the input and the popup, hide it
        if (!$popup.is(e.target) && $popup.has(e.target).length === 0 && !$input.is(e.target) && $input.has(e.target).length === 0) {
            $popup.addClass('hidden');
        }
    });


    $(document).on('click input','#sidebarSearchInput',function(e){
        e.preventDefault();
        const query = $(this).val().trim();
        performSearch(query,'sidebar');

    });

    function renderSidebarSearchResults(groupedResults) {
        const $resultsContainer = $('.search_data ul');
        $resultsContainer.empty();

        const hasResults = Object.keys(groupedResults).length > 0;
        if (!hasResults) {
            $resultsContainer.html('');
            $('.by_default_list').removeClass('hidden');
            $resultsContainer.html('<li><p class="text-gray-500 p-2">No results found.</p></li>');
            return;
        }else{

            $('.by_default_list').addClass('hidden');
        }
        Object.entries(groupedResults).forEach(([category, items]) => {
            items.forEach(item => {
                let assets_field = '';
                if( item.show_videos === true ){                    
                    assets_field += `<a class="flex w-full min-w-[60px] max-w-[60px] h-[60px]" href="${item.url}">
                    <video  width="56px" height="56px" controls class="w-full h-full object-cover">
                    <source src="${item.assets_field}" type="video/mp4">
                    Your browser does not support the video tag.
                    </video>
                    </a>`;
                }else{
                    assets_field +=` <a href="${item.url}" class="flex w-full min-w-[60px] max-w-[60px] h-[60px]">
                    <img width="56px" height="56px" src="${item.assets_field}" alt="${item.title}" class="w-full h-full object-cover">
                    </a>`;
                }
                const listItem = `
                    <li class="flex items-center mb-3">${assets_field}
                    <div class="ps-4">
                    <h5 class="font-matter text-gray-900 hover:text-gray-800 font-medium mb-1 line-clamp-2">
                    <a href="${item.url}" class="flex">${item.title}</a>
                    </h5>
                    <p class="font-matter text-sm text-gray-800">${item.date}</p>
                    </div>
                    </li>`;
                $resultsContainer.append(listItem);
            });
        });
    }
});