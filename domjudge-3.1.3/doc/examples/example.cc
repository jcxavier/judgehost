using namespace std;

#include <iostream>
#include <string>

int main()
{
	int ntests;
	string name;

	cin >> ntests;
	for(int i = 0; i < ntests; i++) {
		cin >> name;
		cout << "Hello " << name << "!" << endl;
	}

	return 0;
}
