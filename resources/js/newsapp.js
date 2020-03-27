$(document).ready(function () {

  let url1 = "http://newsapi.org/v2/top-headlines?country=in&category=business&apiKey=a2a8cddbb7d94ba1a953a2e16d21f24b";
  // let url = "https://newsapi.org/v2/top-headlines?country=us&category=business&apiKey=a2a8cddbb7d94ba1a953a2e16d21f24b";
  $("#searchbtn").on("click",function(e){
    e.preventDefault();
    
    let query = $("#searchquery").val();
    let url = "https://newsapi.org/v2/top-headlines?q="+query+"&country=in&category=business&apiKey=a2a8cddbb7d94ba1a953a2e16d21f24b";
    
    if(query !== ""){
      
      $.ajax({
        
        url: url,
        method: "GET",
        dataType: "json",
        
        beforeSend: function(){
          $("#loader").show();
        },
        
        complete: function(){
          $("#loader").hide();
        },
        
        success: function(news){
          let output = "";
          let latestNews = news.articles;
          
          for(var i in latestNews){
            output +=`
              <div class="col l6 m6 s12">
              <h4>${latestNews[i].title}</h4>
              <img src="${latestNews[i].urlToImage}" class="responsive-img">
              <p>${latestNews[i].description}</p>
              <p>${latestNews[i].content}</p>
              <p>Published on: ${latestNews[i].publishedAt}</p>
              <a href="${latestNews[i].url}" class="btn">Read more</a>
              </div>
            `;
          }
          
          if(output !== ""){
            $("#newsResults").html(output);
             M.toast({
              html: "There you go, nice reading",
              classes: 'green'
            });
            
          }else{
            let noNews = `<div style='text-align:center; font-size:36px; margin-top:40px;'>This news isn't available. Sorry about that.<br>Try searching for something else </div>`;
             $("#newsResults").html(noNews);
            M.toast({
              html: "This news isn't available",
              classes: 'red'
            });
          }
          
        },
        
        error: function(){
           let internetFailure = `
           <div style="font-size:34px; text-align:center; margin-top:40px;">Please check your internet connection and try again.
           <img src="img/internet.png" class="responsive-img">
           </div>`;
           
          $("#newsResults").html(internetFailure);
           M.toast({
              html: "We encountered an error, please try again",
              classes: 'red'
            });
        }
        
        
      });
      
    }else{
      let missingVal = `<div style="font-size:34px; text-align:center; margin-top:40px;">Please enter any search term in the search engine</div>`;
      $("#newsResults").html(missingVal);
       M.toast({
              html: "Please enter something",
              classes: 'red'
            });
    }
    
  });
  $.ajax({
    url: url1,
    method: "GET",
    dataType: "JSON",

    beforeSend: function () {
      $(".progress").show();
    },

    complete: function () {
      $(".progress").hide();
    },

    success: function (newsdata) {
      let output = "";
      let latestNews = newsdata.articles;
      for (var i in latestNews) {
        output += `
          <div class="col l4 m6 s12">
          <div class="card medium hoverable">
            <div class="card-image">
              <img src="${latestNews[i].urlToImage}" class="responsive-img" alt="${latestNews[i].title}">
            </div>
            <div class="card-content">
              <span class="card-title activator"><i class="material-icons right">more_vert</i></span>
              <h6 class="truncate">Title: <a href="${latestNews[i].url}" title="${latestNews[i].title}">${latestNews[i].title}</a></h6>
              <p><b>Author</b>: ${latestNews[i].author} </p>
              <p><b>News source</b>: ${latestNews[i].source.name} </p>
              <p><b>Published</b>: ${latestNews[i].publishedAt} </p>
            </div>

            <div class="card-reveal">
              <span class="card-title"><i class="material-icons right">close</i></span>
              <p><b>Description</b>: ${latestNews[i].description}</p>
            </div>

            <div class="card-action">
              <a href="${latestNews[i].url}" target="_blank" class="btn">Read More</a>
            </div>
           </div>
          </div>
        `;
      }

      if (output !== "") {
        $("#newsResults").html(output);
      }

    },

    error: function () {
      let errorMsg = `<div class="errorMsg center">Some error occured</div>`;
      $("#newsResults").html(errorMsg);
    }
  })

});