<link rel="stylesheet" href="./src/css/shopsettings.css">
<link rel="stylesheet" href="./src/css/shopmessage.css">
<link rel="stylesheet" href="./src/css/chatbox.css">
<link rel="stylesheet" href="./src/css/sass/index.css">
<style>
    .s-profile-input input {
        border: none;
    }
</style>
<main class="shop-main">
    <!-- content -->
    <div class="shop-profile">
        <div class=" message_container" style="margin-top:20px">
            <div class="message_left">
                <div class="m-search">
                    <div class="m-w-search">
                        <div class="search-icon">
                            <i class="fa-solid fa-magnifying-glass"></i>
                        </div>
                        <input type="text" class="ip-search" placeholder="Search">
                    </div>
                </div>
                <div class="m_list_user" id="mess-sidebar">
                </div>
            </div>
            <div class="message_right">
                <div class="mess mess-head">
                    <div class="head-g-left">
                        <div class="wrap-avt">
                            <img class="g-l-avatar" src="https://scontent.fsgn2-6.fna.fbcdn.net/v/t39.30808-6/433196416_987680149581323_4106827378914305018_n.jpg?stp=dst-jpg_p843x403&_nc_cat=111&ccb=1-7&_nc_sid=5f2048&_nc_ohc=FHZyo2k1CvQAX-OcoBP&_nc_ht=scontent.fsgn2-6.fna&oh=00_AfBeio6Z2BZQLW7HvLwWSmuHS-O1bGRk7P1UJtTcRU6MKA&oe=6612A989" alt="">
                            <div class="point"></div>
                        </div>
                        <div class="">
                            <div class="user-name">Huong Ly</div>
                            <div class="status">
                                <div class="">
                                    Dang hoat dong
                                </div>
                                |
                                <span class="tag">
                                    <i class="fa-solid fa-tag"></i>
                                </span>
                            </div>
                        </div>
                    </div>
                    <div class="head-g-right"></div>
                </div>
                <div class="mess mess-body">
                    <!-- <div class="message-left flex">
                        <div class="flex gap-4px w-mess">
                            <div class="">
                                <img class="mess-avatar" src="https://scontent.fsgn2-6.fna.fbcdn.net/v/t39.30808-6/433196416_987680149581323_4106827378914305018_n.jpg?stp=dst-jpg_p843x403&_nc_cat=111&ccb=1-7&_nc_sid=5f2048&_nc_ohc=FHZyo2k1CvQAX-OcoBP&_nc_ht=scontent.fsgn2-6.fna&oh=00_AfBeio6Z2BZQLW7HvLwWSmuHS-O1bGRk7P1UJtTcRU6MKA&oe=6612A989" alt="">
                            </div>
                            <p class="mess-content">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eaque odit quaerat ea sit aliquid quos, minima autem non magni veniam vel cum molestiae, nam labore accusamus, eligendi at praesentium deserunt.</p>
                        </div>
                    </div>
                    <div class="message-left flex">
                        <div class="flex gap-4px w-mess">
                            <div class="">
                                <img class="mess-avatar" src="https://scontent.fsgn2-6.fna.fbcdn.net/v/t39.30808-6/433196416_987680149581323_4106827378914305018_n.jpg?stp=dst-jpg_p843x403&_nc_cat=111&ccb=1-7&_nc_sid=5f2048&_nc_ohc=FHZyo2k1CvQAX-OcoBP&_nc_ht=scontent.fsgn2-6.fna&oh=00_AfBeio6Z2BZQLW7HvLwWSmuHS-O1bGRk7P1UJtTcRU6MKA&oe=6612A989" alt="">
                            </div>
                            <p class="mess-content">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eaque odit quaerat ea sit aliquid quos, minima autem non magni veniam vel cum molestiae, nam labore accusamus, eligendi at praesentium deserunt.</p>
                        </div>
                    </div>
                    <div class="message-left flex">
                        <div class="flex gap-4px w-mess">
                            <div class="">
                                <img class="mess-avatar" src="https://scontent.fsgn2-6.fna.fbcdn.net/v/t39.30808-6/433196416_987680149581323_4106827378914305018_n.jpg?stp=dst-jpg_p843x403&_nc_cat=111&ccb=1-7&_nc_sid=5f2048&_nc_ohc=FHZyo2k1CvQAX-OcoBP&_nc_ht=scontent.fsgn2-6.fna&oh=00_AfBeio6Z2BZQLW7HvLwWSmuHS-O1bGRk7P1UJtTcRU6MKA&oe=6612A989" alt="">
                            </div>
                            <img class="img-content" src="https://images.unsplash.com/photo-1602918955248-d1bbfcbfae38?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8M3x8Zmxhc2h8ZW58MHx8MHx8fDA%3D" alt="">
                        </div>
                    </div>
                    <div class="message-left flex">
                        <div class="flex gap-4px w-mess">
                            <div class="">
                                <img class="mess-avatar" src="https://scontent.fsgn2-6.fna.fbcdn.net/v/t39.30808-6/433196416_987680149581323_4106827378914305018_n.jpg?stp=dst-jpg_p843x403&_nc_cat=111&ccb=1-7&_nc_sid=5f2048&_nc_ohc=FHZyo2k1CvQAX-OcoBP&_nc_ht=scontent.fsgn2-6.fna&oh=00_AfBeio6Z2BZQLW7HvLwWSmuHS-O1bGRk7P1UJtTcRU6MKA&oe=6612A989" alt="">
                            </div>
                            <img class="img-content" src="https://images.unsplash.com/photo-1511268594014-0e9d3ea5c33e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8Zmxhc2h8ZW58MHx8MHx8fDA%3D" alt="">
                        </div>
                    </div>
                    <div class="message-left flex">
                        <div class="flex gap-4px w-mess">
                            <div class="">
                                <img class="mess-avatar" src="https://scontent.fsgn2-6.fna.fbcdn.net/v/t39.30808-6/433196416_987680149581323_4106827378914305018_n.jpg?stp=dst-jpg_p843x403&_nc_cat=111&ccb=1-7&_nc_sid=5f2048&_nc_ohc=FHZyo2k1CvQAX-OcoBP&_nc_ht=scontent.fsgn2-6.fna&oh=00_AfBeio6Z2BZQLW7HvLwWSmuHS-O1bGRk7P1UJtTcRU6MKA&oe=6612A989" alt="">
                            </div>
                            <p class="mess-content">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eaque odit quaerat ea sit aliquid quos, minima autem non magni veniam vel cum molestiae, nam labore accusamus, eligendi at praesentium deserunt.</p>
                        </div>
                    </div>
                    <div class="message-right flex">
                        <div class="flex gap-4px w-mess">
                            <p class="mess-content">Lorem ipsum dolor sit amet consectetur, adipisicing elit. Eaque odit quaerat ea sit aliquid quos, minima autem non magni veniam vel cum molestiae, nam labore accusamus, eligendi at praesentium deserunt.</p>
                        </div>
                    </div>
                    <div class="message-right flex">
                        <div class="flex gap-4px w-mess flex-end">
                            <img class="img-content" src="https://images.unsplash.com/photo-1511268594014-0e9d3ea5c33e?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8Zmxhc2h8ZW58MHx8MHx8fDA%3D" alt="">
                        </div>
                    </div>
                    <div class="mess-right"></div> -->
                </div>
                <div class="mess mess-foot">
                    <div class="image-review" id="files-rev">
                        <!-- image review -->
                        <!-- <div class="relative img-item-rev">
                            <div class="icon-close absolute">
                                <i class="fa-solid fa-x"></i>
                            </div>
                            <img src="https://images.unsplash.com/photo-1511289081-d06dda19034d?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8Mnx8Zmxhc2h8ZW58MHx8MHx8fDA%3D" alt="" class="img-rev">
                        </div> -->
                        <!-- image review -->
                    </div>
                    <form class="flex wrap-input" enctype="multipart/form-data" id="form-message">
                        <input type="file" name="media[]" id="up-file-image" style="display: none;" accept="image/*" multiple>
                        <div class="icon-file">
                            <i class="fa-solid fa-upload"></i>
                        </div>
                        <input type="text" name="message_text" class="input-text input-mess" placeholder="Type some thing">
                        <button type="submit" class="send-icon">
                            <i class="fa-solid fa-paper-plane"></i>
                        </button>
                    </form>

                </div>
            </div>
        </div>
    </div>
</main>
<script src="./src/js/manage-mess.js"></script>
</div>
</div>