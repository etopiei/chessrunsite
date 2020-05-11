<?php
    // First decide if we are having a run posted, otherwise display the form to submit a new run
    if (isset($_POST["time"])) {
        $time = $_POST["time"];
        $time_in_s = 0;
        $parts = explode(":", $time);
        for ($i = 0; $i < sizeof($parts); $i++) {
            if ($i == 0) {
                $time_in_s += 86400 * intval($parts[$i], 10);
            } else  if ($i == 1)  {
                $time_in_s += 3600 * intval($parts[$i], 10);
            } else if ($i == 2) {
                $time_in_s += 60 * intval($parts[$i], 10);
            } else {
                $time_in_s += intval($parts[$i], 10);
            }
        }
        $category = $_POST["category"];
        $link = $_POST["link"];
        $username = $_POST["username"];
        // Now add run to the db, as an unconfirmed run 
        $conn = mysqli_connect('localhost', getenv("DB_USER"), getenv("DB_PASSWORD"), 'chessrun');
        if ($conn) {
            $stmt = $conn->prepare("INSERT INTO runs (username, runtime, link, category_id, confirmed) VALUES (?, ?, ?, ?, 0)");
            $stmt->bind_param("sisi", $username, $time_in_s, $link, $category);
            $stmt->execute();
            $stmt->close();
            $conn->close();
            exit("Submitted Run! <a href='/'>Go Back Home</a>");
        }
        die("Run failed to submit");
    }
?>
<html>
    <head>
		<link rel="Stylesheet" href="main.css" type="text/css" media="screen">
    </head>
    <body>
        <h2>ChessRun Submission</h2>
        <h4>Rules</h4>
        <ul>
            <li>Run must contain video proof (provide a link in the form below)</li>
            <li>Category must be set</li>
            <li>Username must match site that the speedrun was done on</li>
            <li>Runs will not be shown on the leaderboard, until approved by a moderator</li>
        </ul>
        <form method="post">
            <span>Username:</span><input type="text" placeholder="Username" name="username"><br>
            <span>Category</span>
            <div class="select-container">
                <!-- TODO: This can be re-created based on the database contents
                     Then it will automatically update as categories are made
                -->
                <select name="category">
                    <option value="1">Bullet - Chess.com: 1500-2500</option>
                    <option value="2">Blitz - Chess.com: 1500-2500</option>
                    <option value="3">Puzzles - Chess.com: 1500-2500</option>
                    <option value="4">Queen Sacrifice Bullet - Chess.com: 1500-2500</option>
                    <option value="5">Queen Sacrifice Blitz - Chess.com: 1500-2500</option>
                    <option value="6">Queen Sacrifice Blitz - lichess.org: 1500-2500</option>
                    <option value="7">Queen Sacrifice Bullet - lichess.org: 1500-2500</option>
                    <option value="8">Bullet - lichess.org: 1500-2500</option>
                    <option value="9">Blitz - lichess.org: 1500-2500</option>
                    <option value="10">Puzzles - lichess.org: 1500-2500</option>
                    <option value="11">Bongcloud Blitz - lichess.org: 1500-2500</option>
                    <option value="12">Bongcloud Bullet lichess.org:1500-2500</option>
                    <option value="13">Bongcloud Bullet - Chess.com: 1500-2500</option>
                    <option value="14">Bongcloud Blitz - Chess.com: 1500-2500</option>
                </select>
            </div><br>
            <span>Time (dd:hh:mm:ss)</span><input type="text" placeholder="dd:hh:mm:ss" name="time"><br>
            <span>Link</span><input type="text" placeholder="Link to Video" name="link"><br>
            <input type="submit" value="Submit">
        </form>
    </body>
</html>

<style>
    span {
        margin-right: 2em;
    }
    input {
        margin-top: 1em;
        margin-bottom: 1em;
    }
</style>