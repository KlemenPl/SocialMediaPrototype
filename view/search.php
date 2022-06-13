<!DOCTYPE html>
<html>

<head>
    <?php include("view/head_include.php"); ?>
    <meta charset="UTF-8" />
    <title>Main </title>


</head>



<body>
    <?php include("view/navbar.php"); ?>
    <div class="col-lg-6 mx-auto">
        <form class="row g-3">
            <div class="col-md-9">
                <input type="text" oninput="searchInput()" id="searchTerm" name="search" class="form-control" placeholder="Search..." autofocus required minlength="1" maxlength="64">
            </div>
            <div class="col-md-3">
                <select id="searchType" oninput="searchInput()" class="form-select" required>
                    <option selected value="people">People</option>
                    <option value="posts">Posts</option>
                </select>
            </div>
        </form>

        <div class="my-3 p-3 bg-body rounded shadow-sm">
            <h6 class="border-bottom pb-2 mb-0">Results</h6>
            <div id="searchResults">
            </div>
            <div class="d-flex justify-content-center">
                <div id="searchSpinner" class="spinner-border" role="status" style="display: none;">
                    <span class="visually-hidden">Loading...</span>
                </div>
            </div>
        </div>
    </div>
    <?php include("view/footer.php"); ?>

</body>

</html>

<script type="text/javascript">

        function searchInput() {
            const query = document.getElementById("searchTerm").value;
            const type = document.getElementById("searchType").value;
            const spinner = document.getElementById("searchSpinner");

            if (query.length > 0) {
                spinner.style.display = "block";
                $.get("<?=BASE_URL . "api/search" ?>",
                    {
                        query: query,
                        type: type,
                    }, (data) => {
                        //console.log(data);
                        const results = document.getElementById("searchResults");
                        results.innerHTML = "";

                        for (let i = 0; i < data.length; ++i) {
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

                            if (type == "people") {
                                titleStrong.innerHTML = data[i]["display_name"];
                                span.innerHTML = "@" + data[i]["username"];
                                link.innerHTML = "User profile";
                                link.href = "profile?id=" + data[i]["id"];
                            } else {
                                titleStrong.innerHTML = data[i]["title"];
                                span.innerHTML = data[i]["content"];
                                link.innerHTML = "@" + data[i]["username"];
                                link.href = "profile?id=" + data[i]["id"];
                            }

                            results.appendChild(div);
                        }

                        spinner.style.display = "none";

                    });
            }
        }
</script>