$(function () {
  $(".pagetop").click(function () {
    $('html, body').animate({
      scrollTop: 0
    }, 800);
  });
  $(window).scroll(function () {
    if ($(window).scrollTop() > 1) {
      $('.pagetop').fadeIn(300).css('display', 'flex')
    } else {
      $('.pagetop').fadeOut(300)
    }
  });
});

// ページ内スクロール
  var headerH = $("#header").height();	

$('a[href^="#"]').click(function () {
  const speed = 700;
  let href = $(this).attr("href");
  let target = $(href == "#" || href == "" ? "html" : href);
  let position = target.offset().top - headerH;
  $("body,html").animate({ scrollTop: position }, speed, "swing");
  return false;
});


// フッター手前でストップ
$(function () {
  $(".pagetop").hide();
  $(window).on("scroll", function () {
    scrollHeight = $(document).height();
    scrollPosition = $(window).height() + $(window).scrollTop();
    footHeight = $("footer").innerHeight();
    if (scrollHeight - scrollPosition <= footHeight) {
      $(".pagetop").css({
        position: "absolute",
        bottom: footHeight,
      });
    } else {
      $(".pagetop").css({
        position: "fixed",
        bottom: "0",
      });
    }
  });
});



