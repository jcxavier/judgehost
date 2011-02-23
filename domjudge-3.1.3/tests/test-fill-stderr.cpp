/* $Id: test-fill-stderr.cpp 3302 2010-08-09 18:39:20Z eldering $
 *
 * Floods stderr and should fail with TIME-LIMIT or RUN-ERROR
 * depending on whether timelimit or filesize limit is reached first.
 *
 * @EXPECTED_RESULTS@: TIME-LIMIT,RUN-ERROR
 */

using namespace std;

#include <iostream>
#include <string>

int main()
{
	while ( 1 ) cerr << "Fill stderr with nonsense, to test filesystem stability.\n";

	return 0;
}
