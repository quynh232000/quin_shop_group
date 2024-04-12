// <!-- chat box -->

const chatWrap = document.querySelector("#chat-box");
const chatBox = document.querySelector("#chat-box-content");
const chatInput = document.querySelector("#chat-input");
const closeBtn = document.querySelector("#close-btn");
const chatBoxIcon = document.querySelector("#chat-icon");
const chatShop = document.querySelector("#chat_shop");
const scrollBtn = document.querySelector("#scroll-btn");

//

const scrollToBottom = (element) => {
  if (element.scrollHeight > element.clientHeight) {
    element.scrollTop = element.scrollHeight - element.clientHeight;
  }
};
const isNearBottom = (element) => {
  const bottomDistance =
    element.scrollHeight - element.clientHeight - element.scrollTop;
  return bottomDistance <= 20;
};
// =======quynh======
// render list chat
function render_list_chat(data) {
  if (data && data.length > 0) {
    let messageHtml = "";
    data.forEach(function (value) {
      let listImg = JSON.parse(value.message_media);
      let htmlImg = "";
      if (listImg.length > 0) {
        listImg.forEach(function (img) {
          htmlImg += `<img class="chat-item-image" onclick="show_image(this)" src="assest/upload/${img}"/>`;
        });
      }
      let senderClass =
        value.sender_type === "user" ? "chat-send" : "chat-give";
      messageHtml += `<div class="${senderClass} chat-message-body">
                                <div class="chat-content">
                                    ${value.message_text}
                                </div>
                                <div class="chat_list_img">
                                    ${htmlImg}
                                </div>
                            </div>`;
    });
    $("#chat-box-content").html(messageHtml);
    // scrollToBottom(chatBox);
    
  } else {
    $("#chat-box-content").html(
      '<div class="no_mesage">Bạn có thắc mắc gì hãy hỏi đáp cho chúng tôi!</div>'
    );
  }
}
// fetch list data latest
function get_new_message_list(page = 1, limit = 20) {
  const shop_id = $("#chat_shop_id").val();
  $.ajax({
    url: `?mod=request&act=get_conversation_user&shop_id=${shop_id}&page=${page}&limit=${limit}`,
  })
    .done(function (res) {
      res = JSON.parse(res);
      if (res.status) {
        render_list_chat(res.result);
      } else {
        console.log(res.message);
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      console.error("Error:", errorThrown);
    });
}
function send_message(page = 1, limit = 20) {
  $.ajax({
    url: `?mod=request&act=create_message_user&page=${page}&limit=${limit}`,
    type: "POST",
    data: new FormData($("#form_chat-box")[0]),
    processData: false,
    contentType: false
  })
    .done(function (res) {
      res = JSON.parse(res);
      if (res.status) {
        render_list_chat(res.result);
        scrollToBottom(chatBox);
        $("#chat_list_preview").html("");
        $("#chat-input").val("");
        $("#chat_img").val("")
      } else {
        console.log(res.message);
      }
    })
    .fail(function (jqXHR, textStatus, errorThrown) {
      console.error("Error:", errorThrown);
    });
}
// submit send message
$("#form_chat-box").submit(function (e) {
  e.preventDefault();
  send_message();
});
// preview img
$("#chat_img").change(function (e) {
  $("#chat_list_preview").html("");
  const files = e.target.files;
  for (let i = 0; i < files.length; i++) {
    const file = files[i];
    const reader = new FileReader();
    reader.onload = function (e) {
      const url = e.target.result;
      $("#chat_list_preview").append(`
      <div class="chat_preview_img">
            <img src="${url}" onclick="show_image(this)" alt="">
        </div>
      `);
    };
    reader.readAsDataURL(file);
  }
});

// =======quynh======

// const give = (message) => {
//   chatBox.innerHTML += ChatGive(message);
// };

const listenGive = (() => {
  scrollToBottom(chatBox);
  let listenId;
  
  const start = () => {
    get_new_message_list();
    listenId = setInterval(get_new_message_list, 3000);
  };
  const end = () => {
    // clearInterval(listenId);
  };

  return {
    start,
    end,
  };
})();

const toggleChatBox = () => {
  if (chatWrap.classList.contains("hidden")) {
    listenGive.start();
  } else {
    listenGive.end();
  }
  chatWrap.classList.toggle("hidden");
  chatBoxIcon.classList.toggle("hidden");
};

const showScroll = () => {
  if (!isNearBottom(chatBox)) {
    scrollBtn.classList.remove("hidden");
  } else {
    scrollBtn.classList.add("hidden");
  }
};
const closeScroll = () => {
  scrollBtn.classList.add("hidden");
};

const handleClickScroll = () => {
  closeScroll();
  scrollToBottom(chatBox);
};

//handle Envent

// const btnSend = document.querySelector("#btn-send");
// btnSend.addEventListener("click", handleSend);
// chatInput.addEventListener("keydown", (e) => {
//   if (e.key == "Enter") {
//     handleSend();
//   }
// });
closeBtn.addEventListener("click", toggleChatBox);
chatBoxIcon.addEventListener("click", toggleChatBox);
chatShop.addEventListener("click", toggleChatBox);
scrollBtn.addEventListener("click", handleClickScroll);
