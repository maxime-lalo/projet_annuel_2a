#include <stdlib.h>
#include <stdio.h>
#include <string.h>
#include <qrencode.h>
#include <png.h>
#include <dirent.h>
#include <mysql.h>

//DATABASE
MYSQL * initBdd(){
    MYSQL * conn;
	const char * server = "localhost";
	const char * user = "root";
	const char * password = "ac5aabb";
	const char * database = "project_db";
	
	conn = mysql_init(NULL);
	
	/* Connect to database */
	if (!mysql_real_connect(conn, server, user, password, database, 0, NULL, 0)) {
		fprintf(stderr, "%s\n", mysql_error(conn));
		
	}
   return conn;
}

MYSQL_ROW fetchRow(char * request){
	MYSQL * conn = initBdd();
    MYSQL_RES * res;
	MYSQL_ROW row;
	if (mysql_query(conn, request)) {
		fprintf(stderr, "%s\n", mysql_error(conn));
		exit(1);
	}
   
	res = mysql_use_result(conn);
	if (res == NULL)
	{
		return NULL;
	}
	
    row = mysql_fetch_row(res);
	if(row == NULL){
		return NULL;
	}
    return row;
}  

char * fetchColumn(char * request){
    MYSQL_ROW row = fetchRow(request);
	if (row != NULL){
		return row[0];
	}else{
		return NULL;
	}
    
}   
  


// FUNCTIONS
int QRCodeToPng(char *filename, int width, int height, unsigned char *buffer, char *title)
{
	int code = 0;
	FILE *fp = NULL;
	png_structp png_ptr = NULL;
	png_infop info_ptr = NULL;
	png_bytep row = NULL;

	// Open file for writing (binary mode)
	fp = fopen(filename, "wb");
	if (fp == NULL)
	{
		fprintf(stderr, "Could not open file %s for writing\n", filename);
		code = 1;
		goto finalise;
	}
	// Initialize write structure
	png_ptr = png_create_write_struct(PNG_LIBPNG_VER_STRING, NULL, NULL, NULL);
	if (png_ptr == NULL)
	{
		fprintf(stderr, "Could not allocate write struct\n");
		code = 1;
		goto finalise;
	}

	// Initialize info structure
	info_ptr = png_create_info_struct(png_ptr);
	if (info_ptr == NULL)
	{
		fprintf(stderr, "Could not allocate info struct\n");
		code = 1;
		goto finalise;
	}
	// Setup Exception handling
	if (setjmp(png_jmpbuf(png_ptr)))
	{
		fprintf(stderr, "Error during png creation\n");
		code = 1;
		goto finalise;
	}
	png_init_io(png_ptr, fp);

	// Write header (8 bit colour depth)
	png_set_IHDR(png_ptr, info_ptr, width, height,
				 8, PNG_COLOR_TYPE_RGB, PNG_INTERLACE_NONE,
				 PNG_COMPRESSION_TYPE_BASE, PNG_FILTER_TYPE_BASE);

	// Set title
	if (title != NULL)
	{
		png_text title_text;
		title_text.compression = PNG_TEXT_COMPRESSION_NONE;
		title_text.key = "Title";
		title_text.text = title;
		png_set_text(png_ptr, info_ptr, &title_text, 1);
	}

	png_write_info(png_ptr, info_ptr);

	// Allocate memory for one row (3 bytes per pixel - RGB)
	row = (png_bytep)malloc(3 * width * sizeof(png_byte));

	// Write image data
	int x, y;
	for (y = 0; y < height; y++)
	{
		for (x = 0; x < width; x++)
		{
			if ((buffer[y * width + x] % 2) == 1)
			{
				row[x * 3] = 0;
				row[x * 3 + 1] = 0;
				row[x * 3 + 2] = 0;
			}
			else
			{
				row[x * 3] = 255;
				row[x * 3 + 1] = 255;
				row[x * 3 + 2] = 255;
			}
		}
		png_write_row(png_ptr, row);
	}

	// End write
	png_write_end(png_ptr, NULL);

finalise:
	if (fp != NULL)
		fclose(fp);
	if (info_ptr != NULL)
		png_free_data(png_ptr, info_ptr, PNG_FREE_ALL, -1);
	if (png_ptr != NULL)
		png_destroy_write_struct(&png_ptr, (png_infopp)NULL);
	if (row != NULL)
		free(row);

	return code;
}

void getValueFromKey(const char *json_key, const char *json_path, char ** value)
{
	long json_size;
	u_int16_t i;
	
	strcpy(*value, "\0");
	
	FILE *json = fopen(json_path, "r");
	
	if(json != NULL){
		fseek(json, 0, SEEK_END);
		
		json_size = ftell(json);
		
		char *json_string = malloc(json_size);
		fseek(json, 0, SEEK_SET);
		fread(json_string, 1, json_size, json);
		fclose(json);
		
		char *occurence = strstr(json_string, json_key);
		free(json_string);
		if (occurence)
		{
			i = 0;
			char *start_value = occurence + strlen(json_key) + 3;
			while (strncmp(start_value + i, "\"", 1) != 0)
			{
				strncat(*value, start_value + i, 1);
				i++;
			}
		}
	}else{
		printf("[ ERROR ] Could not load json !\n");
		exit(-1);
	}
}

int main(int argc, char const *argv[])
{
	
    char qrcodeValue[780];
    char email[255], firstName[255], lastName[255], id[255];
    char json_path[255], png_path[255];
	char request[300];
	u_int8_t i = 0;
    DIR *d;
	
    struct dirent *dir;
    d = opendir("./new_user");
	
    if (d)
    {
		
		char * temp = malloc(255);
		
        while ((dir = readdir(d)) != NULL)
        {
			if (i > 1)
			{
				strcat(strcpy(json_path, "./new_user/"), dir->d_name);

				getValueFromKey("email", json_path, &temp);
				strcpy(email, temp);


				strcat(strcat(strcpy(request, "SELECT id FROM user WHERE email='"), email), "'");
				strcpy(id, fetchColumn(request));

				getValueFromKey("firstname", json_path, &temp);
				strcpy(firstName, temp);
				getValueFromKey("lastname", json_path, &temp);
				strcpy(lastName, temp);
				
				strcat(strcat(strcat(strcat(strcat(strcat(strcpy(qrcodeValue, "|id:"), id), "-fst:"), firstName), "-lst:"), lastName), "|");
				QRcode *img = QRcode_encodeString8bit(qrcodeValue, 0, QR_ECLEVEL_H);
				strcat(strcpy(json_path, "./new_user"), dir->d_name);

				strcat(strcat(strcpy(png_path, "./qrcode/"), id), ".png");
				QRCodeToPng(png_path, img->width, img->width, img->data, id);
				QRcode_free(img);
				
				remove(json_path);
			}
			i++;
        }
		free(temp);
        closedir(d);
    }

    return 0;
}
