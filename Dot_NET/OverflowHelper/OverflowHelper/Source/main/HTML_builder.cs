﻿/****************************************************************************
 * Copyright (C) 2019 Peter Mortensen                                       *
 * This file is part of OverflowHelper.                                     *
 *                                                                          *
 *                                                                          *
 * Purpose: XXXX                                                            *
 *                                                                          *
 ****************************************************************************/


using System.Text; //For StringBuilder.


/****************************************************************************
 *    <placeholder for header>                                              *
 ****************************************************************************/
namespace OverflowHelper.core
{


    /****************************************************************************
     *    <placeholder for header>                                              *
     ****************************************************************************/
    public class HTML_builder
    {
        const int kSpacesPerIndentLevel = 4;


        StringBuilder mScratchSB;
        StringBuilder mHTMLcontentSB;

        int mIndents; // Actual number of spaces

        string mSpaces; // Cached, direct function of mIndents.


        /****************************************************************************
         *    <placeholder for header>                                              *
         ****************************************************************************/
        public HTML_builder()
        {
            mScratchSB = new StringBuilder(32);

            mHTMLcontentSB = new StringBuilder(1200000);
            mIndents = 0; //Explicit

            changeIndents(0);
        }


        /****************************************************************************
         *    <placeholder for header>                                              *
         ****************************************************************************/
        private void changeIndents(int aChange)
        {
            mIndents += aChange;        
            
            mScratchSB.Length = 0;
            for (int i = 0; i < mIndents; i++)
			{
                mScratchSB.Append(" ");		 
			}
            mSpaces = mScratchSB.ToString();
        }


        /****************************************************************************
         *                                                                          *
         *    Adds the provided raw HTML content                                    *
         *                                                                          *
         *    mHTMLcontentSB should only be changed in this function.               *
         *                                                                          *
         ****************************************************************************/
        private void addContentRaw(string aContent)
        {
            mHTMLcontentSB.Append(aContent);
        }


        /****************************************************************************
         *                                                                          *
         *    Adds an empty line in the HTML content.                                *
         *                                                                          *
         ****************************************************************************/
        public void addEmptyLine()
        {
            addContentRaw("\n");
        }


        /****************************************************************************
         *                                                                          *
         *    Adds the provided HTML content - the current indentation              *
         *    level (in the HTML source) is handled automatically.                  *
         *                                                                          *
         ****************************************************************************/
        public void addContent(string aContent)
        {
            addContentRaw(mSpaces);
            addContentRaw(aContent);
        }


        /****************************************************************************
         *                                                                          *
         *  As addContent(), but with a newline after to                            *
         *  separate it from the following HTML content.                            *
         *                                                                          *
         ****************************************************************************/
        public void addContentOnSeparateLine(string aContent)
        {
            addContent(aContent);
            addContentRaw("\n");
        }


        /****************************************************************************
         *                                                                          *
         *  As addContentOnSeparateLine(), but with a newline before to             *
         *  separate it by an empty line in the HTML content from the               *
         *  previous content (if the previous content added a newline).             *
         *                                                                          *
         ****************************************************************************/
        public void addContentWithEmptyLine(string aContent)
        {
            addContentRaw("\n");
            addContentOnSeparateLine(aContent);
        }
        

        /****************************************************************************
         *                                                                          *
         ****************************************************************************/
        public void indentLevelUp()
        {
            changeIndents(kSpacesPerIndentLevel);
        }


        /****************************************************************************
         *                                                                          *
         ****************************************************************************/
        public void indentLevelDown()
        {
            changeIndents(-kSpacesPerIndentLevel);
        }


        /****************************************************************************
         *                                                                          *
         ****************************************************************************/
        public string singleLineTagStr(string aTagName, string aTagContentText)
        {
            return ("<" + aTagName + ">" + aTagContentText + "</" + aTagName + ">");
        }


        /****************************************************************************
         *                                                                          *
         ****************************************************************************/
        public void singleLineTagOnSeparateLine(
            string aTagName, string aTagContentText)
        {
            addContentOnSeparateLine(singleLineTagStr(aTagName, aTagContentText));
        }


        /****************************************************************************
         *                                                                          *
         ****************************************************************************/
        public void singleLineTagWithEmptyLine(
            string aTagName, string aTagContentText)
        {
            addContentWithEmptyLine(singleLineTagStr(aTagName, aTagContentText));
        }


        /****************************************************************************
         *                                                                          *
         ****************************************************************************/
        public void startTagWithEmptyLine(string aTagName)
        {
            addContentWithEmptyLine("<" + aTagName + ">");
            indentLevelUp();
        }


        /****************************************************************************
         *                                                                          *
         ****************************************************************************/
        public void endTagOneSeparateLine(string aTagName)
        {
            indentLevelDown();
            addContentOnSeparateLine("</" + aTagName + ">");
        }


        /****************************************************************************
         *              
         *  With an empty line before  
         * 
         ****************************************************************************/
        public void addParagraph(string aText)
        {
            singleLineTagWithEmptyLine("p", aText);
        }


        /****************************************************************************
         *                                                                          *
         *  Adds all the start of standard HTML docuemnt, including                 *
         *  the start <head> tag and a title.                                       *
         *                                                                          *
         ****************************************************************************/
        public void startHTML(string aTitle)
        {
            //scratchSB.Append("<!DOCTYPE html>\n");
            addContentOnSeparateLine("<!DOCTYPE html>");

            ////scratchSB.Append("\n");
            ////scratchSB.Append("<html lang=\"en\">\n");
            addContentWithEmptyLine("<html lang=\"en\">");
            //startTagWithEmptyLine("html lang=\"en\""); // This also works, even
            //                                            though it also 
            //                                            has attributes...
            indentLevelUp();

            //scratchSB.Append("\n");
            //scratchSB.Append("    <head>\n");
            //addContentWithEmptyLine("<head>");
            //indentLevelUp();
            startTagWithEmptyLine("head");

            //To make the special characters at the end (e.g. for arrow) actually
            //work on the resulting web opened in the browser
            //scratchSB.Append("    	<meta http-equiv=\"Content-Type\" content=\"text/html; charset=UTF-8\">\n");
            //scratchSB.Append("\n");
            addContentOnSeparateLine(
                "<meta http-equiv=\"Content-Type\" " +
                "content=\"text/html; charset=UTF-8\">");

            //Title
            //scratchSB.Append("        <title>");
            //scratchSB.Append(title);
            //scratchSB.Append("</title>\n");
            //addContentWithEmptyLine("<title>" + aTitle + "</title>");
            singleLineTagWithEmptyLine("title", aTitle);
        }
        

        /****************************************************************************
         *                                                                          *
         *  Returns the current HTML content. The side effect is that               *
         *  the current HTML is cleared (but the current indentation                *
         *  level is maintained)                                                    *
         *                                                                          *
         ****************************************************************************/
        public string currentHTML()
        {
            string toReturn = mHTMLcontentSB.ToString();
            mHTMLcontentSB.Length = 0;
            return toReturn;
        }


    } //class HTML_builder


}


