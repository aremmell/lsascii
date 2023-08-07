<!--
 | index.php
 |
 | Author:    Ryan M. Lederman <lederman@gmail.com>
 | Copyright: Copyright (c) 2023
 | Version:   0.1.0
 | License:   The MIT License (MIT)
 |
 | Permission is hereby granted, free of charge, to any person obtaining a copy of
 | this software and associated documentation files (the "Software"), to deal in
 | the Software without restriction, including without limitation the rights to
 | use, copy, modify, merge, publish, distribute, sublicense, and/or sell copies of
 | the Software, and to permit persons to whom the Software is furnished to do so,
 | subject to the following conditions:
 |
 | The above copyright notice and this permission notice shall be included in all
 | copies or substantial portions of the Software.
 |
 | THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND, EXPRESS OR
 | IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF MERCHANTABILITY, FITNESS
 | FOR A PARTICULAR PURPOSE AND NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR
 | COPYRIGHT HOLDERS BE LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER
 | IN AN ACTION OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN
 | CONNECTION WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 -->
<!doctype html>
<html lang="en">
    <head>
        <title>lsascii | ASCII art generator</title>
        <meta charset="utf-8">
        <meta name="viewport" content="width=device-width, initial-scale=1">
        <meta name="author" content="Ryan M. Lederman <lederman@gmail.com>">

        <!--<meta property="og:title" content="lsascii">

        <meta property="og:description" content="< ?php echo($show_question ? ($appState->get_question_value() . "&nbsp;" . LOC_OG_DESC_PERMALINK) : LOC_MAGIC_EIGHTBALL_DESC); ?>">
        <meta property="og:url" content="< ?php echo($show_question ? $appState->get_permalink() : "https://rml.dev/magic-eightball") ?>">
        <meta property="og:type" content="website">
        <meta property="og:image" content="https://rml.dev/magic-eightball/img/og-image.png">

        <meta name="twitter:card" content="summary_large_image"></meta>
        <meta name="twitter:title" content="< ?php echo_loc_string(LOC_MAGIC_EIGHTBALL); ?>"></meta>
        <meta name="twitter:description" content="< ?php echo($show_question ? ($appState->get_question_value() . "&nbsp;" . LOC_OG_DESC_PERMALINK) : LOC_MAGIC_EIGHTBALL_DESC); ?>"></meta>
        <meta name="twitter:image" content="https://rml.dev/magic-eightball/img/og-image.png"></meta>

        <link rel="icon" href="favicon.png">
        <link rel="icon" href="favicon.ico" sizes="any">
        <link rel="icon" href="icon.svg" type="image/svg+xml">
        <link rel="apple-touch-icon" href="apple-touch-icon.png">
        <link rel="manifest" href="icons.webmanifest">-->

        <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-rbsA2VBKQhggwzxH7pPCaAqO46MgnOM80zW1RWuH61DGLwZJEdK2Kadq2F9CUG65" crossorigin="anonymous">
        <link rel="stylesheet" href="css/index.css">

        <script src="js/jquery-3.6.4.min.js"></script>
    </head>

    <?php
        require("font-util.php");
        $_query = empty($_GET['q']) ? "" : urldecode($_GET['q']);
    ?>

    <body>
        <div class="container-fluid">
            <?php
                if (empty($_query)) {
                    echo <<<EOS
                        <div class="no-query">
                            ERROR: query missing; append '?q=input' to URL.
                        </div>
                        EOS;
                } else {
                    $arr = array();
                    $fu  = new font_util();
                    if (!$fu->get_ascii_fonts($fu->get_font_dir(), $arr)) {
                        echo "<p>Failed to list fonts!</p>";
                    }

                    foreach ($arr as $font) {
                        $ascii = array();
                        if ($fu->exec_figlet($font, $_query, $ascii)) {
                            $font        = substr(strrchr($font, "/"), 1);
                            $ascii_lines = "";

                            foreach ($ascii as $line) {
                                if (!empty($line)) {
                                    $line = str_replace(" ", "&#160;", $line);
                                    $ascii_lines .= "<span>" . htmlentities($line, ENT_HTML5 | ENT_QUOTES | ENT_SUBSTITUTE,
                                        "UTF-8", false) . "</span>" . "<br/>";
                                }
                            }

                            echo <<<EOS
                                <div class="font-entry">
                                    <hr/>
                                    <p><b>{$font}</b></p>
                                    <div class="font-entry-ascii">
                                        {$ascii_lines}
                                    </div>
                                </div>
                            EOS;
                        } else {
                            echo "Failed to execute ASCII generator!";
                        }
                    }
                }
            ?>
        </div>
        <div class="footer">
            <div class="footer-entry"></div>
        </div>
        <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.2.3/dist/js/bootstrap.min.js" integrity="sha384-cuYeSxntonz0PPNlHhBs68uyIAVpIIOZZ5JqeqvYYIcEL727kskC66kF92t6Xl2V" crossorigin="anonymous"></script>
    </body>
</html>
