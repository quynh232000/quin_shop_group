// // const baseUrl = `http://quinapi.mr-quynh.com`;
// // const baseUrl = `http://localhost/pro1014/quin_group/`;

// function convert_image(image) {
//   return image.includes("https") ? image : "assest/upload/" + image;
// }

// const baseUrl = ``;

// const getAllRoom = async () => {
//   const url = `${baseUrl}?mod=request&act=get_list_conversation_shop`;
//   const res = await fetch(url);
//   const json = await res.json();
//   const data = json;
//   return await data.result;
// };

// const getRoomById = async (id) => {
//   const url = `${baseUrl}?mod=request&act=get_conversation_shop&user_id=${id}`;
//   const res = await fetch(url);
//   const json = await res.json();
//   const data = json;
//   return await data.result;
// };

// const addMess = async () => {
//   const url = `${baseUrl}?mod=request&act=create_message_shop`;
//   const data = new FormData(document.querySelector("#form-message"));
//   data.append("user_id", state.curIdRoom);
//   const option = {
//     method: "POST",
//     body: data,
//   };
//   fetch(url, option)
//     .then((res) => res.json())
//     .then((data) => {
//       if (data.status) {
//         ChatBox.udMess(data.result);
//       }
//     });
// };

// const scrollToBottom = (element) => {
//   if (element.scrollHeight > element.clientHeight) {
//     element.scrollTop = element.scrollHeight - element.clientHeight;
//   }
// };

// const timeSend = (targetTime) => {
//   const targetTimestamp = new Date(targetTime).getTime();
//   const currentTimestamp = new Date().getTime();
//   const difference = currentTimestamp - targetTimestamp;

//   // Calculate the difference in seconds, minutes, hours, and days
//   const seconds = Math.floor(difference / 1000);
//   const minutes = Math.floor(seconds / 60);
//   const hours = Math.floor(minutes / 60);
//   const days = Math.floor(hours / 24);

//   // Return the time difference as an object
//   return {
//     days: days,
//     hours: hours % 24,
//     minutes: minutes % 60,
//     seconds: seconds % 60,
//   };
// };

// const div = (className) => {
//   const div = document.createElement("div");
//   div.setAttribute("class", className);
//   return div;
// };

// const state = {
//   curIdRoom: localStorage.getItem('curIdRoom')?JSON.parse(localStorage.getItem('curIdRoom')):"",
//   curAvatar: localStorage.getItem('curAvatar')?JSON.parse(localStorage.getItem('curAvatar')):"",
//   curName: localStorage.getItem('curName')?JSON.parse(localStorage.getItem('curName')):"",
//   lsImage: [],
// };

// //components
// const SidebarItem = ({
//   full_name,
//   user_avatar,
//   message_text,
//   user_id,
//   message_created_at,
// }) => {
//   const time = timeSend(message_created_at);
//   const timeAt =
//     time.days > 0
//       ? `${time.days} ngày`
//       : time.hours > 0
//       ? `${time.hours} giờ`
//       : `${time.minutes} phút`;
//   const children = `
//     <span data-idroom="${user_id}" class="side-bar-item dp-content flex-1">
//         <div class="m_user_img">
//             <img src="${convert_image(user_avatar)}" alt="">
//         </div>
//         <div class="m_user-body">
//             <div class="m_user-top">
//                 <div class="m_user_name">${full_name}</div> <span>${timeAt}</span>
//             </div>
//             <div class="m_user-text">${message_text}</div>
//         </div>
//     </span>
//     `;
//   const divElm = div("m_user_item");
//   divElm.innerHTML = children;
//   return divElm;
// };

// const MessLeft = ({ message_text, message_media, avavtar }) => {
//   const imgaeMedia = JSON.parse(message_media);
//   let html_imgs = "";
//   if (imgaeMedia.length > 0) {
//     html_imgs = imgaeMedia
//       .map((item) => {
//         return `
//             <img class="img-content" onclick="show_image(this)" src="${convert_image(
//               item
//             )}" alt="">
//             `;
//       })
//       .join("");
//   }
//   const children = `
//    <div class="flex gap-4px w-mess">
//    <div class="mess-avatar">
//             <img class="w-100 avt-cover" src="${convert_image(
//               state.curAvatar
//             )}" alt="">
//     </div>
//          <div class="s_m_left">
//          ${
//            message_text === undefined ||
//            message_text === null ||
//            message_text.trim() === ""
//              ? ""
//              : `<p class="mess-content">${message_text}</p>`
//          }
//          <div class="s_list_img">${html_imgs}</div>
//          </div>
//     </div>
//     `;

//   const divEml = div("message-left flex");
//   divEml.innerHTML = children;
//   return divEml;
// };

// const MessRight = ({ message_text, message_media }) => {
//   const imgaeMedia = !!message_media ? JSON.parse(message_media) : [];

//   let html_imgs = "";
//   if (imgaeMedia.length > 0) {
//     html_imgs = imgaeMedia
//       .map(() => {
//         return `
//             <img class="img-content" onclick="show_image(this)" src="${convert_image(
//               item
//             )}" alt="">
//             `;
//       })
//       .join("");
//   }
//   const children = `
//     <div class="flex gap-4px w-mess flex-end">
//         ${
//           message_text === undefined ||
//           message_text === null ||
//           message_text.trim() === ""
//             ? ""
//             : `<p class="mess-content">${message_text}</p>`
//         }
//         <div class="s_list_img">${html_imgs}</div>
//     </div>
//     `;

//   const divEml = div("message-right flex");
//   divEml.innerHTML = children;
//   return divEml;
// };

// const ImageRev = (image) => {
//   const children = `
//     <div class="icon-close absolute">
//         <i class="fa-solid fa-x"></i>
//     </div>
//     <img src="${image}" onclick="show_image(this)" alt="" class="img-rev">
//     `;
//   const divElm = div("relative img-item-rev");
//   divElm.innerHTML = children;
//   return divElm;
// };

// const ChatSidebar = (() => {
//   const sidebar = document.querySelector("#mess-sidebar");
//   sidebar.innerHTML = "";

//   const active = (e) => {
//     document
//       .querySelectorAll(".m_user_item")
//       .forEach((item) => item.classList.remove("active"));
//     const item = e.target.closest(".m_user_item");
//     item.classList.add("active");
//   };

//   const atClick = () => {
//     sidebar.addEventListener("click", (e) => {
//       const item = e.target.closest(".side-bar-item");
//       if (item) {
//         const curId = item.dataset.idroom;
//         state.curIdRoom = curId;
//         const image = item.querySelector("img");
//         const startIndex = image.src.indexOf("assest/upload/");
//         state.curAvatar = image.src.substring(startIndex);
//         state.curName = item.querySelector(".m_user_name").innerHTML;

//         localStorage.setItem('curIdRoom',JSON.stringify(curId))
//         localStorage.setItem('curAvatar',JSON.stringify(state.curAvatar))
//         localStorage.setItem('curName',JSON.stringify(state.curName))

//         // scrollToBottom(document.querySelector(".mess-body"));
//         ChatBox.update();
//         active(e);
//         let id_interval = setInterval(()=>{
//             ChatBox.update();
//         },3000)
//       }
//     });
//   };
//   const init = async () => {
//     const data = await getAllRoom();
//     // check data localstorage
//     if(data.length >0 ){
//         if((state.curIdRoom =='')){
//                 state.curIdRoom = data[0].user_id
//                 state.curAvatar=data[0].user_avatar
//                 state.curName = data[0].full_name
//                 ChatBox.update()
//         }
//         data.forEach((item) => {
//             sidebar.append(SidebarItem(item));
//           });
//           atClick();
//     }else{
//         $("#mess-sidebar").html('<div class="no-product">Bạn không có đoạn chat nào!</div>')
//     }

   
//   };

//   return {
//     init,
//   };
// })();

// const ChatBox = (() => {
//   const boxChat = document.querySelector(".message_right");
//   const userNameElm = boxChat.querySelector(".user-name");
//   const messElm = boxChat.querySelector(".mess-body");

//   const send = (mess) => {
//     messElm.append(MessRight({ message_text: mess }));
//     if (state.lsImage.length > 0) {
//       state.lsImage.forEach((item) => {
//         messElm.append(MessRight({ message_media: JSON.stringify([item]) }));
//       });
//       FileRev.removeAll();
//     }
//     scrollToBottom(messElm);
//   };

//   //

//   const udName = (name) => {
//     userNameElm.innerHTML = name;
//   };

//   const udImage = (image) => {
//     document.querySelector(".g-l-avatar").src = image;
//   };

//   const udMess = (data) => {
//     messElm.innerHTML = "";
//     let pre = "";
//     data.forEach((item) => {
//       if (item.sender_type == "user") {
//         let avt = "";
//         if (pre !== item.sender_type) {
//           avt = state.curAvatar;
//           pre = item.sender_type;
//         } else {
//           avt = "";
//         }
//         messElm.append(MessLeft({ ...item, avavtar: avt }));
//       } else {
//         messElm.append(MessRight({ ...item }));
//       }
//     });
    
//   };

//   const update = async () => {
//     const lsMess = await getRoomById(state.curIdRoom);
//     udName(state.curName);
//     udImage(state.curAvatar);
//     udMess(lsMess);
//   };
//   return {
//     update,
//     send,
//     udMess
//   };
// })();
// // quynh
// if(state && state.curIdRoom){
//     ChatBox.update()
// }
// // quynh
// const MessInput = (() => {
//   const formEml = document.querySelector("#form-message");
//   const inputElm = document.querySelector(".input-text.input-mess");

//   const init = () => {
//     formEml.addEventListener("submit", (e) => {
//       e.preventDefault();
//       {
//         send();
//         formEml.value = "";
//       }
//     });
//   };
//   const send = async () => {
//     const messContent = inputElm.value;
//     await addMess();
//     // ChatBox.send(messContent);
//     // inputElm.value = "";
//     // const inputElm = document.querySelector(".input-text.input-mess");
//     const files = document.querySelector("#files-rev");
//     inputElm.value=""
//     files.innerHTML=""
//   };


//   return {
//     send,
//     init,
//   };
// })();

// const FileRev = (() => {
//   const files = document.querySelector("#files-rev");

//   const removeAll = () => {
//     document.querySelectorAll(".img-item-rev").forEach((i) => {
//       files.removeChild(i);
//     });
//     state.lsImage.length = 0;
//   };
//   const remove = (e) => {
//     const item = e.target.closest(".img-item-rev");
//     files.removeChild(item);
//   };

//   const append = (image) => {
//     files.append(ImageRev(image));
//   };

//   const updateState = (e) => {
//     const item = e.target.closest(".img-item-rev");
//     const image = item.querySelector("img").src;
//     state.lsImage = state.lsImage.filter((i) => i !== image);
//   };

//   const init = () => {
//     files.addEventListener("click", (e) => {
//       const xbtn = e.target.closest(".icon-close");
//       if (xbtn) {
//         remove(e);
//         updateState(e);
//       }
//     });
//   };
//   return { init, append, removeAll };
// })();

// const InputFile = (() => {
//   const input = document.querySelector("#up-file-image");
//   const icon = document.querySelector(".icon-file");
//   const upload = () => {
//     if (input.files && input.files[0]) {
//       for (let i = 0; i < input.files.length; i++) {
//         const reader = new FileReader();
//         reader.onload = function (e) {
//           // Display the uploaded image
//           FileRev.append(e.target.result);
//           state.lsImage.push(e.target.result);
//         };
//         // Read the file as a data URL
//         reader.readAsDataURL(input.files[i]);
//       }
//       document.querySelector(".input-text.input-mess").focus();
//     } else {
//       alert("Please select an image.");
//     }
//   };
//   const init = () => {
//     input.addEventListener("change", upload);
//     icon.addEventListener("click", () => {
//       input.click();
//     });
//   };
//   return { init };
// })();

// const main = () => {
//   ChatSidebar.init();
//   MessInput.init();
//   FileRev.init();
//   InputFile.init();
// };

// document.addEventListener("DOMContentLoaded", main);






// ======new
// const baseUrl = `http://quinapi.mr-quynh.com`;
// const baseUrl = `http://localhost/pro1014/quin_group/`;

function convert_image(image) {
  return image.includes("https") ? image : "assest/upload/" + image;
}

const baseUrl = ``;

const getAllRoom = async () => {
  const url = `${baseUrl}?mod=request&act=get_list_conversation_shop`;
  const res = await fetch(url);
  const json = await res.json();
  const data = json;
  return await data.result;
};

const getRoomById = async (id) => {
  const url = `${baseUrl}?mod=request&act=get_conversation_shop&user_id=${id}`;
  const res = await fetch(url);
  const json = await res.json();
  const data = json;
  return await data.result;
};

const addMess = async () => {
  const url = `${baseUrl}?mod=request&act=create_message_shop`;
  const data = new FormData(document.querySelector("#form-message"));
  data.append("user_id", state.curIdRoom);
  const option = {
      method: "POST",
      body: data,
  };
  fetch(url, option)
      .then((res) => res.json())
      .then((data) => {
          if (data.status) {
              ChatBox.udMess(data.result);
              scrollToBottom(state.messElm);
          }
      });
};

const scrollToBottom = (element) => {
  if (element.scrollHeight > element.clientHeight) {
      element.scrollTop = element.scrollHeight - element.clientHeight;
  }
};

const timeSend = (targetTime) => {
  const targetTimestamp = new Date(targetTime).getTime();
  const currentTimestamp = new Date().getTime();
  const difference = currentTimestamp - targetTimestamp;

  // Calculate the difference in seconds, minutes, hours, and days
  const seconds = Math.floor(difference / 1000);
  const minutes = Math.floor(seconds / 60);
  const hours = Math.floor(minutes / 60);
  const days = Math.floor(hours / 24);

  // Return the time difference as an object
  return {
      days: days,
      hours: hours % 24,
      minutes: minutes % 60,
      seconds: seconds % 60,
  };
};

const div = (className) => {
  const div = document.createElement("div");
  div.setAttribute("class", className);
  return div;
};

const state = {
  curIdRoom: localStorage.getItem("curIdRoom")
      ? JSON.parse(localStorage.getItem("curIdRoom"))
      : "",
  curAvatar: localStorage.getItem("curAvatar")
      ? JSON.parse(localStorage.getItem("curAvatar"))
      : "",
  curName: localStorage.getItem("curName")
      ? JSON.parse(localStorage.getItem("curName"))
      : "",
  lsImage: [],
  trackMess: {},
  messElm: document.querySelector(".mess-body"),
};

//components
const SidebarItem = ({
  full_name,
  user_avatar,
  message_text,
  user_id,
  message_created_at,
}) => {
  const time = timeSend(message_created_at);
  const timeAt =
      time.days > 0
          ? `${time.days} ngày`
          : time.hours > 0
          ? `${time.hours} giờ`
          : `${time.minutes} phút`;
  const children = `
  <span data-idroom="${user_id}" class="side-bar-item dp-content flex-1">
      <div class="m_user_img">
          <img src="${convert_image(user_avatar)}" alt="">
      </div>
      <div class="m_user-body">
          <div class="m_user-top">
              <div class="m_user_name">${full_name}</div> <span>${timeAt}</span>
          </div>
          <div class="m_user-text">${message_text}</div>
      </div>
  </span>
  `;
  const divElm = div("m_user_item");
  divElm.innerHTML = children;
  return divElm;
};

const MessLeft = ({ message_text, message_media, avavtar }) => {
  const imgaeMedia = JSON.parse(message_media) || [];
  let html_imgs = "";
  if (imgaeMedia.length > 0) {
      html_imgs = imgaeMedia
          .map((item) => {
              return `
          <img class="img-content" onclick="show_image(this)" src="${convert_image(
              item
          )}" alt="">
          `;
          })
          .join("");
  }
  const children = `
 <div class="flex gap-4px w-mess">
 <div class="mess-avatar">
          <img style="height:100%" onclick="show_image(this)" class="w-100 avt-cover h-100" src="${convert_image(
              state.curAvatar
          )}" alt="">
  </div>
       <div class="s_m_left">
       ${
           message_text === undefined ||
           message_text === null ||
           message_text.trim() === ""
               ? ""
               : `<p class="mess-content">${message_text}</p>`
       }
       <div class="s_list_img">${html_imgs}</div>
       </div>
  </div>
  `;

  const divEml = div("message-left flex");
  divEml.innerHTML = children;
  return divEml;
};

const MessRight = ({ message_text, message_media }) => {
  const imgaeMedia = !!message_media ? JSON.parse(message_media) : [];

  let html_imgs = "";
  if (imgaeMedia.length > 0) {
      html_imgs = imgaeMedia
          .map((item) => {
              return `
          <img class="img-content" onclick="show_image(this)" src="${convert_image(
              item
          )}" alt="">
          `;
          })
          .join("");
  }
  const children = `
  <div class="flex gap-4px w-mess flex-end">
      ${
          message_text === undefined ||
          message_text === null ||
          message_text.trim() === ""
              ? ""
              : `<p class="mess-content">${message_text}</p>`
      }
      <div class="s_list_img">${html_imgs}</div>
  </div>
  `;

  const divEml = div("message-right flex");
  divEml.innerHTML = children;
  return divEml;
};

const ImageRev = (image) => {
  const children = `
  <div class="icon-close absolute">
      <i class="fa-solid fa-x"></i>
  </div>
  <img src="${image}" onclick="show_image(this)" alt="" class="img-rev">
  `;
  const divElm = div("relative img-item-rev");
  divElm.innerHTML = children;
  return divElm;
};

const ChatSidebar = (() => {
  const sidebar = document.querySelector("#mess-sidebar");
  let id_interval = null;
  sidebar.innerHTML = "";

  const active = (e) => {
      document
          .querySelectorAll(".m_user_item")
          .forEach((item) => item.classList.remove("active"));
      const item = e.target.closest(".m_user_item");
      item.classList.add("active");
  };

  const atClick = () => {
      sidebar.addEventListener("click", (e) => {
          const item = e.target.closest(".side-bar-item");

          if (item) {
              const curId = item.dataset.idroom;
              state.curIdRoom = curId;
              const image = item.querySelector("img");
              const startIndex = image.src.indexOf("assest/upload/");
              const new_avatar = image.src.substring(startIndex);
              state.curAvatar =   new_avatar.replace(/^assest\/upload\//, "");
              state.curName = item.querySelector(".m_user_name").innerHTML;

              localStorage.setItem("curIdRoom", JSON.stringify(curId));
              localStorage.setItem(
                  "curAvatar",
                  JSON.stringify(state.curAvatar)
              );
              localStorage.setItem("curName", JSON.stringify(state.curName));

              ChatBox.update();
              active(e);

              if (!!id_interval) {
                  clearInterval(id_interval);
              }
              state.trackMess = {};
              id_interval = setInterval(() => {
                  ChatBox.udMess();
                  // scrollToBottom(state.messElm);
              }, 1000);
          }
      });
  };
  const init = async () => {
      const data = await getAllRoom();
      // check data localstorage
      if (data.length > 0) {
          if (state.curIdRoom == "") {
              state.curIdRoom = data[0].user_id;
              state.curAvatar = data[0].user_avatar;
              state.curName = data[0].full_name;
          }
          data.forEach((item) => {
              sidebar.append(SidebarItem(item));
          });
          atClick();
      } else {
          $("#mess-sidebar").html(
              '<div class="no-product">Bạn không có đoạn chat nào!</div>'
          );
      }
  };

  return {
      init,
  };
})();

const ChatBox = (() => {
  const boxChat = document.querySelector(".message_right");
  const userNameElm = boxChat.querySelector(".user-name");
  const messElm = boxChat.querySelector(".mess-body");

  // const send = (mess) => {
  //     messElm.append(MessRight({ message_text: mess }));
  //     if (state.lsImage.length > 0) {
  //         state.lsImage.forEach((item) => {
  //             messElm.append(
  //                 MessRight({ message_media: JSON.stringify([item]) })
  //             );
  //         });
  //         FileRev.removeAll();
  //     }
  //     scrollToBottom(messElm);
  // };

  //

  const track = (mess, item) => {
      if (!state.trackMess[item.id]) {
          messElm.append(mess);
          state.trackMess[item.id] = true;
      }
  };
  const udName = (name) => {
      userNameElm.innerHTML = name;
  };

  const udImage = (image) => {
      document.querySelector(".g-l-avatar").src = "assest/upload/"+image;
  };

  const udMess = async (data) => {
      let pre = "";
      if (!data) {
          data = await getRoomById(state.curIdRoom);
      }
      data.forEach((item) => {
          if (item.sender_type == "user") {
              let avt = "";
              if (pre !== item.sender_type) {
                  avt = state.curAvatar;
                  pre = item.sender_type;
              } else {
                  avt = "";
              }
              track(MessLeft({ ...item, avavtar: avt }), item);
          } else {
              track(MessRight({ ...item }), item);
          }
      });
  };

  const update = async () => {
      const lsMess = await getRoomById(state.curIdRoom);
      messElm.innerHTML = "";
      udName(state.curName);
      udImage(state.curAvatar);
      udMess(lsMess);
  };
  return {
      update,
      udMess,
  };
})();
// quynh

// quynh
const MessInput = (() => {
  const formEml = document.querySelector("#form-message");
  const inputElm = document.querySelector(".input-text.input-mess");

  const init = () => {
      formEml.addEventListener("submit", (e) => {
          e.preventDefault();
          {
              send();
              formEml.value = "";
          }
      });
  };
  const send = async () => {
      const messContent = inputElm.value;
      await addMess();
      // ChatBox.send(messContent);
      // inputElm.value = "";
      // const inputElm = document.querySelector(".input-text.input-mess");
      const files = document.querySelector("#files-rev");
      inputElm.value = "";
      files.innerHTML = "";
  };

  return {
      send,
      init,
  };
})();

const FileRev = (() => {
  const files = document.querySelector("#files-rev");

  const removeAll = () => {
      document.querySelectorAll(".img-item-rev").forEach((i) => {
          files.removeChild(i);
      });
      state.lsImage.length = 0;
  };
  const remove = (e) => {
      const item = e.target.closest(".img-item-rev");
      files.removeChild(item);
  };

  const append = (image) => {
      files.append(ImageRev(image));
  };

  const updateState = (e) => {
      const item = e.target.closest(".img-item-rev");
      const image = item.querySelector("img").src;
      state.lsImage = state.lsImage.filter((i) => i !== image);
  };

  const init = () => {
      files.addEventListener("click", (e) => {
          const xbtn = e.target.closest(".icon-close");
          if (xbtn) {
              remove(e);
              updateState(e);
          }
      });
  };
  return { init, append, removeAll };
})();

const InputFile = (() => {
  const input = document.querySelector("#up-file-image");
  const icon = document.querySelector(".icon-file");
  const upload = () => {
      if (input.files && input.files[0]) {
          for (let i = 0; i < input.files.length; i++) {
              const reader = new FileReader();
              reader.onload = function (e) {
                  // Display the uploaded image
                  FileRev.append(e.target.result);
                  state.lsImage.push(e.target.result);
              };
              // Read the file as a data URL
              reader.readAsDataURL(input.files[i]);
          }
          document.querySelector(".input-text.input-mess").focus();
      } else {
          alert("Please select an image.");
      }
  };
  const init = () => {
      input.addEventListener("change", upload);
      icon.addEventListener("click", () => {
          input.click();
      });
  };
  return { init };
})();

const main = () => {
  if (state && state.curIdRoom) {
      ChatBox.update();
  }
  ChatSidebar.init();
  MessInput.init();
  FileRev.init();
  InputFile.init();
};

document.addEventListener("DOMContentLoaded", main);
