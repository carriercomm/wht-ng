#include <fstream>
/**
*    Web Hosting Toolkit - Next Generation (WHT-NG)
*    Copyright (C) 2014  Jimmy M. Coleman <hyperclock@ok.de>
*    Copyright (C) 2003  Nikolay Ivanov <nivanov@email.com> (GPLv2)
*
*    This program is free software: you can redistribute it and/or modify
*    it under the terms of the GNU General Public License as published by
*    the Free Software Foundation, either version 3 of the License, or
*    (at your option) any later version.
*
*    This program is distributed in the hope that it will be useful,
*    but WITHOUT ANY WARRANTY; without even the implied warranty of
*    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
*    GNU General Public License for more details.
*
*    You should have received a copy of the GNU General Public License
*    along with this program.  If not, see <http://www.gnu.org/licenses/>.
*/

using namespace std;

char buffer[1024];
ifstream file;

main()
{
    printf("Content-Type: text/html\n\n");

    file.open(getenv("PATH_TRANSLATED"));

    if(!file.is_open()) {
        printf("Can't open file!");
        return 0;
    } else {
        while(!file.eof()) {
            file.getline(buffer, 1024);
            printf(buffer);
            printf("\n");
        }
    }

//wht

    file.close();

    return 0;

}
