<?php
    # Purposes (though we should probably split the WordPress-specific
    #           parts into a separate file, as this file has now
    #           taken on more responsibilities):
    #
    #
    #   1. Centralise the WordPress-specific things. E.g. to minimise
    #      redundancy on individual pages.
    #
    #      We also have the option to turn off WordPress (e.g.
    #      to avoid a lot of noise in HTML validation).
    #
    #   2. Centralise things (by means of helper functions) to eliminate
    #      some redundancy (e.g. Edit Overflow version).


    # Only used by one page (Text.php)
    const MAINTEXT = 'someText';
    $formDataSizeDiff = -1;


    # Including version number and date
    #
    # Note that we are using the WordPress convention of
    # name prefixing functions (with "get_") that
    # return a value (no side effects).
    #
    function get_EditOverflowID()
    {
        return "Edit Overflow v. 1.1.49a5 2019-12-18T200610Z+0";
    }


    # Note that we are using the WordPress convention of
    # name prefixing functions (with "the_") that echo's.
    #
    function the_EditOverflowHeadline($aHeadline)
    {
        echo "<h1>$aHeadline - " . get_EditOverflowID() . "</h1>";

        # Another side-effect of this function... Use the
        # opportunity as this function is used by
        # all pages (and in the beginning).
        adjustForWordPressMadness();
    }


    # Helper function to support switching between WordPress
    # and native 1990s HTML (the original version)
    #
    function useWordPress()
    {
        # That is, if we pass OverflowStyle (by GET or POST), we
        # can turn off the WordPress part (e.g. to ease HTML
        # validation (for example, when using WordPress, we
        # got 29 issues in total (14 errors and 15 warnings)
        # for "EditSummaryFragments.php")).
        #
        # Examples:
        #
        #    <https://pmortensen.eu/world/EditOverflow.php?LookUpTerm=cpu&OverflowStyle=Native>
        #
        #    <https://pmortensen.eu/world/EditSummaryFragments.php?OverflowStyle=Native>
        #
        #    <https://pmortensen.eu/world/Text.php?OverflowStyle=Native>


        # The default is to use WordPress, if not explicitly turned off
        # (and by the exact spelling of the value of "OverflowStyle"
        # that may be absent). However, this is the ***single***
        # place to unconditionally turn off WordPress, if needed.
        #
        $toReturn = true;

        $OverflowStyle = $_REQUEST['OverflowStyle'] ?? 'WordPress';
        if ($OverflowStyle === 'Native')
        {
            #echo "<p>WordPress styling turned off...</p>\n";
            $toReturn = false;
        }
        else
        {
            #echo "<p>WordPress styling retained...</p>\n";
        }
        return $toReturn;
    } #useWordPress()


    # Single quotes, etc. are currently escaped if
    # using WordPress (that is, in the form data).
    #
    # Note: Currently it is only done for a specific element,
    #       "someText" (used by a specific page, Text.php)
    #
    function adjustForWordPressMadness()
    {
        global $_REQUEST;
        #global MAINTEXT;
        global $formDataSizeDiff;

        # When we open the form (URL with ".php") there isn't
        # form data.
        if (array_key_exists(MAINTEXT, $_REQUEST))
        {
            $formDataSizeBefore = strlen($_REQUEST[MAINTEXT]);

            #echo "<p>formDataSizeBefore: $formDataSizeBefore</p>\n";

            # Only when WordPress is active (otherwise we get errors)
            if (function_exists('stripslashes_deep'))
            {
                # Escape problem "fix" (ref. <https://stackoverflow.com/a/33604648>)
                # The problem is solely due to WordPress (we would't need it
                # if it wasn't for the use of/integration into WordPress).
                #
                # "stripslashes_deep" is part of WordPress
                #
                $_REQUEST = array_map('stripslashes_deep', $_REQUEST);
            }

            $formDataSizeAfter = strlen($_REQUEST[MAINTEXT]);

            # Note: Only for one specific element,
            #       "someText", only used in Text.php.
            #
            $formDataSizeDiff = $formDataSizeBefore - $formDataSizeAfter;

            #echo "<p>formDataSizeDiff: $formDataSizeDiff</p>\n";
        }
    }


    # Single place for output of dynamic "value" attributes in HTML forms.
    function the_formValue($aRawContentlinkInlineMarkdown)
    {
        # Later: Make it more robust - encode double
        #        quote (") as "&quot;". Probably
        #        also single quote.

        echo "value=\"$aRawContentlinkInlineMarkdown\"\n";
    }


    # ########   E n d   o f   f u n c t i o n   d e f i n i t i o n s   ########



    ###########################################################################
    # WordPress specific!

    if (useWordPress())
    {
        # For getting the styling and other redundant
        # content (like headers) from WordPress.
        #
        # So now we have a dependency on WordPress...
        #
        define('WP_USE_THEMES', false);
        require(dirname(__FILE__) . '/wp-blog-header.php');

        get_header(); # Note: Using some WordPress themes results in the following
                      #       on the page itself (though we also have it in the
                      #       title for all themes - but this is less intrusive):
                      #
                      #           "Page not found"
                      #
                      #       Some themes that do not give it are:
                      #
                      #           "Responsive"    (the one we currently use)
                      #           "Orfeo"
                      #           "Hestia"
                      #           "Astra"
    }
    else
    {
        # Revert to old-style header
        #
        # Yes, the Heredoc style makes it ugly.
        #
        echo <<<'HTML_END'
<!DOCTYPE html>
<html lang="en">

    <head>
        <meta http-equiv="Content-Type" content="text/html; charset=UTF-8">

        <title>Some edit summary fragments - Edit Overflow</title>

        <style>
            body
            {
                background-color: lightgrey;
            }
        </style>


        <!-- ================= From EditOverflow.php - to be merged in the
                               next round of refactoring -->
        <style>
            body
            {
                background-color: lightgrey;
            }

            .formgrid
            {
                display: grid;
                grid-template-columns: minmax(5%, 130px) 1em 2fr;
                                       /* 10% 1fr 2fr 12em;
                                          1fr 1em 2fr
                                       */
                grid-gap: 0.3em 0.6em;
                grid-auto-flow: row;
                align-items: center;
            }

            input,
            output,
            textarea,
            select,
            button
            {
                grid-column: 2 / 4;
                width: auto;
                margin: 0;
            }

            .formgrid > div
            {
                grid-column: 3 / 4;
                width: auto;
                margin: 0;
            }

            /* label, */
            input[type="checkbox"] + label,
            input[type="radio"]    + label
            {
                grid-column: 3 / 4;
                width: auto;
                padding: 0;
                margin: 0;
            }

            input[type="checkbox"],
            input[type="radio"]
            {
                grid-column: 2 / 3;
                justify-self: end;
                margin: 0;
            }

            label + textarea
            {
                align-self: start;
            }
        </style>


    </head>

    <body>
        <h1>(Note: PoC, to be styled to escape the 1990s...)</h1>

HTML_END;

        # End of Heredoc part.

    } # End of native HTML part (not WordPress)

?>


