<!--
 | font-util.php
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
<?php
    class font_util
    {
        private $FONT_DIR      = "./fonts";
        private $ASCII_BINARY  = "figlet";
        private $ASCII_WIDTH   = 100;
        private $ASCII_CONTROL = "utf8.flc";

        public function get_font_dir(): string
        {
            return $this->FONT_DIR;
        }

        public function get_path_in_font_dir(string $file): string
        {
            return $this->get_font_dir() . "/" . $file;
        }

        public function get_ascii_fonts(string $dir, array &$fonts): bool
        {
            if (is_dir($dir)) {
                $handle = opendir($dir, null);
                if ($handle !== false) {
                    do {
                        $next = readdir($handle);
                        if ($next !== false) {
                            if ($next === "." || $next === "..")
                                continue;

                            $next = $dir . "/" . $next;

                            /* recurse into subdirectories. */
                            if (is_dir($next)) {
                                if (!$this->get_ascii_fonts($next, $fonts)) {
                                    error_log("get_ascii_fonts({$next}) failed!");
                                    continue;
                                }
                            }

                            if (str_ends_with($next, ".flf")) {
                                $fonts[] = $next;
                            }
                        }
                    } while ($next !== false);
                    closedir($handle);
                    asort($fonts);
                    return true;
                }
            }
            return false;
        }

        public function exec_figlet(string $font, string $input, array &$output): bool
        {
            $output = array();

            if (empty($font) || empty($input))
                return false;

            $font  = escapeshellarg($font);
            $input = escapeshellarg($input);

            $shell_cmd = escapeshellcmd(sprintf("%s -w %d -C %s -f %s %s", $this->ASCII_BINARY,
                $this->ASCII_WIDTH, $this->get_path_in_font_dir($this->ASCII_CONTROL), $font, $input));

            $result_code = 1;
            $exec_retval = exec($shell_cmd, $output, $result_code);

            if ($exec_retval != false || $result_code === 0 && count($output) > 0)
                return true;

            return false;
        }
    };
?>
