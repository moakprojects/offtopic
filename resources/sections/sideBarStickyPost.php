<?php 
    if (isset($_SESSION["user"]) && $_SESSION["user"]["loggedIn"]) {
        $postObj = new Post();
        $stickyPosts = $postObj -> getAllSidebarSticky();
        foreach($stickyPosts as $stickyPost) {
?>
            <div class="stickyPost">
                <div class="row noMargin">
                    <h4 class="col s9 noPadding"><i class="fas fa-thumbtack"></i> <?php echo $stickyPost["stickyPostTitle"]; ?></h4>
                    <?php if(isset($_SESSION["user"]["isAdmin"])) { ?>
                        <div class="col s1 pencilIcon titleIcon center-align">
                            <a href='#' class="tooltipped" data-position="bottom" data-tooltip="Edit">
                                <i class="fas fa-pencil-alt fa-xs"></i>
                            </a>
                        </div>
                        <div class="col s1 trashIcon titleIcon center-align">
                            <a onclick="adminDelition('<?php echo $_GET["page"]; ?>', '.sideBar', 'sticky', <?php echo $stickyPost["stickyPostID"] ; ?>)" class="tooltipped" data-position="bottom" data-tooltip="Delete">
                                <i class="fas fa-trash fa-xs"></i>
                            </a>
                        </div>
                    <?php } ?>
                </div>
                <div class="line"></div>
                <div class="stickyContent">
                    <p><?php echo $stickyPost["stickyPostText"]; ?> </p>
                </div>
            </div>
<?php
        }
    }
?>