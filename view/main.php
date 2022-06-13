<!DOCTYPE html>
<html>

<head>
    <?php include("view/head_include.php"); ?>
    <meta charset="UTF-8" />
    <title>Main </title>
</head>

<body class="d-flex flex-column h-100">
    <?php include("view/navbar.php"); ?>

    <div class="col-lg-9 mx-auto">
        <div id="pposts" class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Posts</h6>
            <div id="posts">
            </div>
            <div class="d-flex justify-content-center">
                <div id="loadSpinner" class="spinner-border" role="status" style="">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>

    <?php include("view/footer.php"); ?>

</body>

</html>
<script type="text/javascript">
        const type = "<?= str_ends_with($_SERVER['REQUEST_URI'], "/feed") ? "feed" : "popular"?>";

        const posts = document.getElementById("posts");
        const spinner = document.getElementById("loadSpinner");
        let last_id = -1;

        function distFromBottom() {
            var scrollPosition = window.pageYOffset;
            var windowSize = window.innerHeight;
            var bodyHeight = document.body.offsetHeight;
            return Math.max(bodyHeight - (scrollPosition + windowSize), 0);

        }

        function addPost(post) {
            const div = document.createElement("div");
            div.classList.add("d-flex", "text-muted", "pt-3");

            const contentDiv = document.createElement("div");
            contentDiv.classList.add("pb-3", "mb-0", "small", "lh-sm", "border-bottom", "w-100");
            div.appendChild(contentDiv);

            const mainDiv = document.createElement("div");
            mainDiv.classList.add("d-flex", "justify-content-between");
            contentDiv.appendChild(mainDiv);

            const titleStrong = document.createElement("strong");
            titleStrong.classList.add("text-gray-dark");
            mainDiv.appendChild(titleStrong);

            const link = document.createElement("a");
            mainDiv.appendChild(link);

            const span = document.createElement("span");
            span.classList.add("d-block");
            contentDiv.appendChild(span);

            titleStrong.innerHTML = post["title"];
            span.innerHTML = post["content"];
            link.innerHTML = "@" + post["username"];
            link.href = "profile?id=" + post["user_id"];

            last_id = post["post_id"];

            posts.appendChild(div);
        }

        let isLoading = true;
        spinner.style.display = "block"
        $.get("<?=BASE_URL . "api/load_feed" ?>",
            {
                type: type
            }, (data) => {
                //console.log(data);
                for (let i = 0; i < data.length; ++i) {
                    addPost(data[i]);
                }
                //console.log(data);
                isLoading = false;
                spinner.style.display = "none";
            });

        window.onscroll = function(ev) {
            if (!isLoading && (window.innerHeight + window.scrollY) >= document.body.scrollHeight) {
                // Load more
                spinner.style.display = "block"
                $.get("<?=BASE_URL . "api/load_feed" ?>",
                {
                    type: type,
                    last_id : last_id
                }, (data) => {
                    //console.log(data);
                    if (data != null) {
                        for (let i = 0; i < data.length; ++i) {
                            addPost(data[i]);
                        }
                        isLoading = false;
                    }
                    spinner.style.display = "none";
                });
            }
        };
        
</script>