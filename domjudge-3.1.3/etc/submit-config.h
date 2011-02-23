// Generated from 'submit-config.h.in' on Tue Feb 22 18:05:31 WET 2011.

#ifndef _SUBMIT_CONFIG_
#define _SUBMIT_CONFIG_

/* Define the command to copy files to the user submit directory in the
   submit client differently under Windows and Linux */
#if defined(__CYGWIN__) || defined(__CYGWIN32__)
#define COPY_CMD "c:/cygwin/bin/cp"
#else
#define COPY_CMD "cp"
#endif

/* Submission methods and default. */
#define SUBMIT_DEFAULT    2
#define SUBMIT_ENABLE_CMD 0
#define SUBMIT_ENABLE_WEB 1

/* Default TCP port to use for command-line submit client/deamon. */
#define SUBMITPORT   9147

/* Team HOME subdirectory to use for storing temporary/log/etc. files
   and permissions. */
#define USERDIR      ".domjudge"
#define USERPERMDIR  0700
#define USERPERMFILE 0600

/* Last modified time in minutes after which to warn for submitting
   an old file. */
#define WARN_MTIME   5

/* Maximum source size in KB before warning (this is also the limit
   the server will enforce). */
#define SOURCESIZE   256

/* Auto-detected language extensions by the submit client.
   Format: 'LANG,MAINEXT[,EXT]... [LANG...]' where:
   - LANG is the language name displayed,
   - MAINEXT is the extension corresponding to the extension in DOMjudge,
   - EXT... are comma separated additional detected language extensions.
*/
#define LANG_EXTS    "C,c C++,cpp,cc,c++ Java,java Pascal,pas,p Haskell,hs,lhs Perl,pl Bash,sh"

#endif /* _SUBMIT_CONFIG_ */
