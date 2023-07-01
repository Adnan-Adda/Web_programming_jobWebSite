<!--sök och filter formulär-->
<div class="search" id="search">
    <div class="search-links"> <!--buttons for small screens-->
        <a class="search-link "  href="#"><em><span aria-hidden="true" class="fa-solid fa-magnifying-glass"></span> Sök</a></em>
        <a class="filter-link "  href="#"><em><span aria-hidden="true" class="fa-solid fa-filter"></span> Filter</a></em>
    </div>

    <div id="filter" class="hide"> <!--filter form-->
        <form  action="index.php?action=filter" method="POST">

                <h4>Filtrera annonser</h4>
                 <div class="mb-2">
                    <label for="job-area" class="form-label">Yrkesområde</label>
                    <select class="form-select" name="job_area" id="job-area">
                        <option value="">välj yrkesområde</option>
                        <?= get_select_options($job_options) ?>
                    </select>
                </div>
                 <div class="mb-2">
                    <label for="province" class="form-label me-2">Län </label>
                    <select class="form-select" name="province" id="province">
                        <option value="">välj Län</option>
                        <?= get_select_options($provinces) ?>
                    </select>
                </div>
                <button type="submit" class="btn btn-primary">Filtrera</button>
                <button class="btn btn-danger filter-link" type="button">stäng</button>

        </form>
    </div>

    <div id="keyword-search" class="hide"> <!--keyword search form-->
        <form action="index.php?action=search" method="POST">
                <h4>Sök på annonsers titlar</h4>
                <label for="keyword" class="form-label">Ange sök ord</label>
                <div class="input-group">
                    <input type="text" id="keyword" name="keyword" class="form-control" placeholder="">
                    <button class="btn btn-success" type="submit">Sök</button>
                </div>
                <button class="btn btn-danger mt-2 search-link" type="button">stäng</button>

        </form>
    </div>

</div>

