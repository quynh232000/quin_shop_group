import "https://cdn.quilljs.com/1.3.6/quill.js";

// ==============function start===================//
function handleOpenNavMobile() {
  $("#menumobile").click(function () {
    $(".sidebar").css("left", "0%");
    $(".sidebar-wrapper").css("transform", "translateX(0%)");
  });
  $(".sidebar-close i").click(function () {
    $(".sidebar").css("left", "100%");
    $(".sidebar-wrapper").css("transform", "translateX(100%)");
  });
  $(".sidebar").click(function () {
    $(".sidebar").css("left", "100%");
    $(".sidebar-wrapper").css("transform", "translateX(100%)");
  });
  $(".sidebar-wrapper").click(function (e) {
    e.stopPropagation();
  });
}
function handleShowSortCollection() {
  $(".c-nav-hhiden").click(function () {
    $(".g-left").css("display", "block");
    $(".g-left").click(function (e) {
      e.stopPropagation();
      setTimeout(() => {
        $(".g-left").css("display", "none");
      }, 1000);
    });
  });
}
function handleTreeView() {
  $(".tree-item-icon").click(function () {
    if ($(this).parent().hasClass("has")) {
      const ulChild = $(this).parent().siblings();
      if ($(this).parent().siblings().hasClass("active")) {
        ulChild.removeClass("active");
      } else {
        ulChild.addClass("active");
      }
    }
  });
}
function handleReferralTab() {
  $(".referral-tab-item").click(function () {
    const typeActive = $(this).attr("type");
    $(".referral-tab-item").each(function () {
      $(this).removeClass("active");
      const typeActive1 = $(this).attr("type");
      $(`.referral-content.${typeActive1}`).removeClass("active");
    });
    $(this).addClass("active");
    console.log(typeActive);
    $(`.referral-content.${typeActive}`).addClass("active");
  });
}
const ONE = document.querySelector.bind(document);
function Validator(selector, options) {
  if (!options) {
    options = {};
  }

  function getParent(element, selector) {
    while (element.parentElement) {
      if (element.parentElement.matches(selector)) {
        return element.parentElement;
      }
      element = element.parentElement;
    }
  }

  var formRules = {};
  var validatorRules = {
    required: function (value) {
      return value ? undefined : "Please enter this field";
    },
    email: function (value) {
      const regex = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
      return regex.test(value) ? undefined : "It must be an email";
    },
    min: function (min) {
      return function (value) {
        return value.length >= min ? undefined : `its min ${min} keys`;
      };
    },
  };
  var formElement = document.querySelector(selector);
  if (formElement) {
    var inputs = formElement.querySelectorAll("[name][rules]");
    for (var input of inputs) {
      var rules = input.getAttribute("rules").split("|");
      for (var rule of rules) {
        var ruleInfo;
        var isRuleHasValue = rule.includes(":");
        if (isRuleHasValue) {
          ruleInfo = rule.split(":");
          rule = ruleInfo[0];
        }
        var ruleFunc = validatorRules[rule];
        if (isRuleHasValue) {
          ruleFunc = ruleFunc(ruleInfo[1]);
        }
        if (Array.isArray(formRules[input.name])) {
          formRules[input.name].push(ruleFunc);
        } else {
          formRules[input.name] = [ruleFunc];
        }
      }

      // lawng nghe input

      input.onblur = handleValidate;
      input.oninput = handleClearError;
    }
    // ham onblur
    function handleValidate(e) {
      var rules = formRules[e.target.name];
      var errorMessage;

      for (var rule1 of rules) {
        errorMessage = rule1(e.target.value);
        if (errorMessage) break;
      }

      // neu co loi hienj message loi
      if (errorMessage) {
        var formGroup = getParent(e.target, ".form-body");
        if (formGroup) {
          formGroup.classList.add("invalid");
          if (formGroup.nextElementSibling) {
            formGroup.nextElementSibling.innerText = errorMessage;
          }
        }
      }
      return !errorMessage;

      // console.log(errorMessage)
    }

    function handleClearError(e) {
      var formGroup = getParent(e.target, ".form-body");
      if (formGroup.classList.contains("invalid")) {
        formGroup.classList.remove("invalid");
        if (formGroup.nextElementSibling) {
          formGroup.nextElementSibling.innerText = "";
        }
      }
    }
    // xu li hanh vi submit
    formElement.onsubmit = function (e) {
      e.preventDefault();

      var inputs = formElement.querySelectorAll("[name][rules]");
      var isValid = true;

      for (var input of inputs) {
        if (!handleValidate({ target: input })) {
          isValid = false;
        }
      }
      if (isValid) {
        if (ONE(".loading")) ONE(".loading").style.display = "flex";
        if (typeof options.onSubmit === "function") {
          var enableInputs = formElement.querySelectorAll("[name]");
          var formValues = Array.from(enableInputs).reduce(function (
            values,
            input
          ) {
            switch (input.type) {
              case "radio":
                values[input.name] = formElement.querySelector(
                  'input[name="' + input.name + '"]:checked'
                ).value;
                break;
              case "checkbox":
                if (!input.matches(":checked")) {
                  values[input.name] = "";
                  return values;
                }
                if (!Array.isArray(values[input.name])) {
                  values[input.name] = [];
                }
                values[input.name].push(input.value);
                break;
              case "file":
                values[input.name] = input.files;
                break;
              default:
                values[input.name] = input.value;
            }
            return values;
          },
            {});
          options.onSubmit(formValues);
        }
      }
    };
  }
}
function formartPrice(number) {
  const VND = new Intl.NumberFormat("vi-VN", {
    style: "currency",
    currency: "VND",
  });
  return VND.format(number);
}

// -----------------------------------------------------seller------------------------------//
function handleCreateProduct() {
  let num = 0;

  //modal edit category
  if ($("#select-category-product").length) {
    $("#select-category-product").click(function () {
      $(".modal-edit-cate").css("display", "flex");
    });
  }
  $(document).on("click", ".btn-modal-cate-close", function () {
    $(".modal-edit-cate").css("display", "none");
  });
  //get more category
  $(".modal-cate-item").each(() => {
    $(this).click(() => {
      $(this).addClass("active");
      console.log($(this).attr("checkLast"));
    });
  });
  $(".modal-cate-item").click(function () { });
  //add attribute
  $(".create-btn-add-attibute").click(function () {
    const attrHtml = `<div class="attr-item">
            <div class="attr-no">
                ${num++}
            </div>
            <div class="attr-size">
                <input type="text" name="ProductProperties_Size" value="" placeholder="X, L, M,..." />
            </div>
            <div class="attr-color">
                <input type="color" name="ProductProperties_Color" value="" placeholder="Red, pink,blue,..." />
            </div>
            <div class="attr-quantity">
                <input type="number" name="ProductProperties_Quantity" value="" placeholder="0" />
            </div>
            <div class="attr-price">
                <input type="number" name="ProductProperties_Price" value="" placeholder="$ 0" />
            </div>
            <div class="attr-remove"> <i class="fa-solid fa-trash-can"></i></div>
        </div>`;
    $(".create-pro-attr-body").css("display", "flex");
    $(".attr-list").append(attrHtml);
    //- remove attribute
    $(".attr-list .attr-remove").click(function () {
      const parent = $(this).parent();
      if (parent) parent.empty();
    });
  });
  //show img
  $("input[name='image']").on("change", function (e) {
    const [file] = e.target.files;
    if (file) {
      $(".label-image-cover").css("display", "none");
      $(".create-show-image-body").css("display", "flex");
      $(".create-show-image").attr("src", URL.createObjectURL(file));
    }
  });
  //show list img
  $("input[name='listImage[]").on("change", function (e) {
    const [...files] = e.target.files;
    const listImg = files
      .map((file) => {
        return `
                <div class="product-img">
                    <div class="product-img-wrapper">
                        <img src="${URL.createObjectURL(file)}" />
                        <div class="product-img-delete">
                            <i class="fa-regular fa-trash-can"></i>
                        </div>
                    </div>
                </div>
            `;
      })
      .join("");
    $(".total-img").text(files.length + "");
    $(".label-list-img").css("display", "none");
    $(".list-img-preview").html(listImg);
    //delete img
    $(".product-img-delete").click(function () {
      const parent = $(this).parent().parent();
      if (parent) parent.empty();
    });
  });
  // fill data quilljs
  // update product
  $("#form-create-product").on("submit", function () {
    $("#description").val($("#editor").html());
  });
}
// ==============function end===================//

$().ready(function () {
  // -----------home page start----------//
  // show sidebar
  if ($(".menumobile")) {
    handleOpenNavMobile();
  }
  // show short collection
  if ($(".g-left").length) {
    handleShowSortCollection();
  }
  // tree view
  if ($(".referral-body")) {
    handleTreeView();
    handleReferralTab();
  }
  // -----------home page end----------//

  // -----------shop owner page start---------------------------//
  if ($("#form-create-product").length) {
    if ($("#editor").length) {
      var quill = new Quill("#editor", {
        theme: "snow",
      });
    }
    handleCreateProduct();
  }

  if ($(".detail-img-item img").length) {
    $(".detail-img-item img").each(function () {
      $(this).mouseover(() => {
        $(".detail-img-show img").attr("src", $(this).attr("src"));
      });
    });
  }
  // plus and dminus in detail
  $(".detail-btn-count").click(function () {
    if ($(this).attr("type") == "plus") {
      $(".detail-input-quantity").val(+$(".detail-input-quantity").val() + 1);
    } else {
      if (+$(".detail-input-quantity").val() > 1) {
        $(".detail-input-quantity").val(+$(".detail-input-quantity").val() - 1);
      }
    }
  });
  $(".search-input-product").focus(function (e) {
    $(".header__search-history ").toggle();
  });
  // update profile
  $("#avatar-input").on("input", function () {
    const files = $(this).prop("files");
    $(".profile-show-img").attr("src", URL.createObjectURL(files[0]));
  });
  // upload file cate
  $(".cate-up-image").on("input", function () {
    const files = $(this).prop("files");
    $(".cate-img-preview").attr("src", URL.createObjectURL(files[0]));
  });
  
  // show more desciption
  $(".detail-des-btn-more").click(function () {
    $(".detail-description-body").css("height", "fit-content");
    $(".detail-des-btn-more-wrapper").css("display", "none");
  });
  // show product suggesstion
  $(".suggestion-nav-item").click(function () {
    $(".suggestion-nav-item").each(function () {
      $(this).removeClass("active");
    });
    const id = $(this).attr("cateid");
    $(this).addClass("active");
    $.ajax({
      url: "?mod=request&act=fillterproduct&id=" + id,
    }).done((res) => {
      const resJson = JSON.parse(res);
      if (resJson.status) {
        const data = resJson.result;
        if (data.length > 0) {
        }
        const html = data
          .map((item) => {
            return `
            <div class="product">
            <div class="product-wrapper">
                <a href="?mod=page&act=detail&id=${item.id
              }" class="product-info">
                    <div class="product-sale-label">
                        <svg width="48" height="50" viewBox="0 0 48 50" fill="none"
                            xmlns="http://www.w3.org/2000/svg">
                            <g filter="url(#filter0_d_604_13229)">
                                <path
                                    d="M4.49011 0C3.66365 0 2.99416 0.677946 2.99416 1.51484V11.0288V26.9329C2.99416 30.7346 5.01545 34.2444 8.28604 36.116L20.4106 43.0512C22.6241 44.3163 25.3277 44.3163 27.5412 43.0512L39.6658 36.116C42.9363 34.2444 44.9576 30.7346 44.9576 26.9329V11.0288V1.51484C44.9576 0.677946 44.2882 0 43.4617 0H4.49011Z"
                                    fill="#F5C144" />
                            </g>
                            <defs>
                                <filter id="filter0_d_604_13229" x="-1.00584" y="0" width="49.9635" height="52"
                                    filterUnits="userSpaceOnUse" color-interpolation-filters="sRGB">
                                    <feFlood flood-opacity="0" result="BackgroundImageFix" />
                                    <feColorMatrix in="SourceAlpha" type="matrix"
                                        values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 127 0" result="hardAlpha" />
                                    <feOffset dy="4" />
                                    <feGaussianBlur stdDeviation="2" />
                                    <feComposite in2="hardAlpha" operator="out" />
                                    <feColorMatrix type="matrix"
                                        values="0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0 0.25 0" />
                                    <feBlend mode="normal" in2="BackgroundImageFix"
                                        result="effect1_dropShadow_604_13229" />
                                    <feBlend mode="normal" in="SourceGraphic" in2="effect1_dropShadow_604_13229"
                                        result="shape" />
                                </filter>
                            </defs>
                        </svg>
                        <span>-%
                           ${item.percent_sale}
                        </span>
                    </div>
                    <div class="product-img">
                        <img src="./assest/upload/${item.image_cover}" alt="">
                    </div>
                    <div class="product-brand">
                        ${item.brand}
                    </div>
                    <div class="product-name">
                        ${item.name}
                    </div>
                    <div class="product-stars">
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <i class="fa-solid fa-star"></i>
                        <span>(1.1k)</span>
                    </div>
                    <div class="product-price">
                        <div class="product-price-sale fm-price">
                        ${item.price}
                        </div>
                        <del class="product-price-old fm-price">
                        ${item.price * (1 + item.percent_sale / 100)}
                           
                        </del>
                    </div>
                </a>

                <div onclick="update_cart_user('add','${item.id}',1)" class="product-btn" idpro="${item.id}" data-price= "${
              item.price
            }">

                    <i class="fa-solid fa-cart-plus"></i>
                    <span>Thêm giỏ hàng</span>
                </div>
            </div>
        </div>
            `;
          })
          .join("");
        $(".suggest-list-products").html(html);
        $(".sg-btn-more").on("click", function () {
          location.href = "?mod=page&act=collection&category=" + id;
        });
        // format price
        const VND = new Intl.NumberFormat("vi-VN", {
          style: "currency",
          currency: "VND",
        });
        const prices = document.querySelectorAll(".fm-price");
        prices.forEach((item) => {
          if(!isNaN(item.textContent)){
            item.textContent = VND.format(item.textContent);

          }
        });
      }
    });
    // end ajax
  });
  // run time mega sale
  if ($(".mega-time").length) {
    $(".mega-time").each(function () {
      const type = $(this).attr("type");

      switch (type) {
        case "hour":
          $(this).text(randomTime(1, 12) + "");
          break;
        case "minute":
          $(this).text(randomTime(1, 59) + "");
          break;
        default:
          let timenow = randomTime(1, 59);
          setInterval(() => {
            if (timenow < 1) {
              timenow = 59;
            } else {
              timenow--;
            }
            $(this).text(timenow + "");
          }, 1000);
          break;
      }
    });
  }

  // btn add cart user in detail page
  $('.detail_btn_add').click(function () {
    const type = $(this).attr('data_type')
    const id = $(this).attr('product_id')
    const quantity = $('#input_quantity').val()
    if(isNaN(quantity)){
      toastjs("Số lượng phải là số",false)
      return
    }
    if(quantity >20){
      toastjs("Vui lòng liên hệ với cửa hàng để nhập giá sỉ!")
      return
    }
    if(quantity <=0){
      toastjs("Số lượng sản phẩm không hợp lệ. Vui lòng chọn lại!",false)
      return
    }
    if(type =='add_cart'){
      update_cart_user('plus',id,quantity)
      return
    }
    if(type =='buy_now'){
      update_cart_user('plus',id,quantity)
      setTimeout(() => {
        window.location.href = "?mod=page&act=cart"
      }, 2500);
    }
  })
  
  

  // ==========================================================================================================================//
});
// =================funtions====================//
function randomTime(min, max) {
  return Math.floor(Math.random() * max) + min;
}

const toastEl = document.getElementById("toast");
if (toastEl) {
  const mesType = toastEl.getAttribute("mes-type");
  const mesTitle = toastEl.getAttribute("mes-title");
  const mesText = toastEl.getAttribute("mes-text");
  toast({
    title: mesTitle,
    message: mesText,
    type: mesType,
    duration: 4000,
  });
}
function toast({ title = "", message = "", type = "info", duration = 3000 }) {
  const main = document.getElementById("toast");
  if (main) {
    const toast = document.createElement("div");

    // Auto remove toast
    const autoRemoveId = setTimeout(function () {
      main.removeChild(toast);
    }, duration + 1000);

    // Remove toast when clicked
    toast.onclick = function (e) {
      if (e.target.closest(".toast__close")) {
        main.removeChild(toast);
        clearTimeout(autoRemoveId);
      }
    };

    const icons = {
      success: "fas fa-check-circle",
      info: "fas fa-info-circle",
      warning: "fas fa-exclamation-circle",
      error: "fas fa-exclamation-circle",
    };
    const icon = icons[type];
    const delay = (duration / 1000).toFixed(2);

    toast.classList.add("toast", `toast--${type}`);
    toast.style.animation = `slideInLeft ease .3s, fadeOut linear 1s ${delay}s forwards`;

    toast.innerHTML = `
                    <div class="toast__icon">
                        <i class="${icon}"></i>
                    </div>
                    <div class="toast__body">
                        <h3 class="toast__title">${title}</h3>
                        <p class="toast__msg">${message}</p>
                    </div>
                    <div class="toast__close">
                        <i class="fas fa-times"></i>
                    </div>
                `;
    main.appendChild(toast);
  }
}

function toastjs(text, type = true) {
  var x = document.getElementById("snackbar");

  x.className = "show";
  if (type == false) {
    x.classList.add("toast-error");
  }
  x.textContent = text;
  setTimeout(function () {
    x.className = x.className.replace("show", "");
  }, 3000);
}

// funstion debounse
function debounce(func, delay) {
  let debounceTimer;
  return function () {
    const context = this;
    const args = arguments;
    clearTimeout(debounceTimer);
    debounceTimer = setTimeout(() => func.apply(context, args), delay);
  };
}
const searchInput = document.querySelector(".search-input-product");
if (searchInput) {
  searchInput.oninput = debounce(function (e) {
    const keysearch = e.target.value;
    if (keysearch) {
      $.ajax({
        url: "?mod=request&act=seach_home&keysearch=" + keysearch,
      }).done((res) => {
        res = JSON.parse(res);
        const products = res.result.products;
        if (res.status && (products.length>0)) {
          const html = products
            .map((item) => {
              return `
                  <li class="header__search-history-item">
                    <a href="?mod=page&act=detail&product=${item.slug}" class="h-s-item" >
                        <div class="h-s-img">
                            <img src="./assest/upload/${item.image_cover}" alt="">
                        </div>
                        <div class="h-s-info">
                            <div class="h-s-name">${item.name}</div>
                            <div class="h-s-brandd">${item.brand} - ${item.origin}</div>
                        </div>
                    </a>
                </li>
            `;
            })
            .join("");
          $(".search-totel").text(products.length);
          $("#search_product_result").html(html);
        } else {
          $(".search-totel").text("0");
          $("#search_product_result").html(
            `<div class="no-product">Không tìm thấy sản phẩm nào</div>`
          );
        }
        // shop
        const shops = res.result.shops;
        if (res.status && (shops.length>0)) {
          const html = shops
            .map((item) => {
              return `
                  <li class="header__search-history-item">
                    <a href="?mod=page&act=shop&uuid=${item.uuid}" class="h-s-item" >
                        <div class="h-s-img" id="seearch_img-shop">
                            <img src="./assest/upload/${item.icon}" alt="">
                        </div>
                        <div class="h-s-info">
                            <div class="h-s-name" id="h-shop-name">${item.name}</div>
                        </div>
                    </a>
                </li>
            `;
            })
            .join("");
          $(".search-totel").text(shops.length);
          $("#search_shop_result").html(html);
        } else {
          $(".search-totel").text("0");
          $("#search_shop_result").html(
            `<div class="no-product">Không tìm thấy kết quả nào</div>`
          );
        }
      });
    }
  }, 1000);
}





