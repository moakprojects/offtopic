<div class="sideBarBlock">
    <h4>Latest Posts</h4>
    <div class="line"></div>
    <div class="latestPostsContainer">
        <ul>
            <?php
                $postObj = new Post();
                $latestPosts = $postObj->getLatestPosts();

                foreach($latestPosts as $latestPost) {
            ?>
            <li>
                <span></span>
                    <div class="title"><?php echo $latestPost["shortTopicName"];?></div>
                    <div class="info"><?php echo $latestPost["shortPostText"];?></div>
                    <div class="name">- <?php echo "<a href='/profile/" . $latestPost["username"] . "'>" . $latestPost["username"] . "</a>";?> -</div>
                <div class="time"><span><?php echo $latestPost["monthDay"];?></span><span><?php echo $latestPost["time"];?></span></div>
            </li>
            <?php
                }
            ?>
        </ul>
    </div>
</div>