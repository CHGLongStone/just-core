XC_TYPE_PHP Cache Type = php opcode
XC_TYPE_VAR Cache Type = variable data

mixed xcache_get(string name)
bool  xcache_set(string name, mixed value [, int ttl])
bool  xcache_isset(string name)
bool  xcache_unset(string name)
bool  xcache_unset_by_prefix(string prefix)
int   xcache_inc(string name [, int value [, int ttl]])
int   xcache_dec(string name [, int value [, int ttl]])

Administrator Functions
int    xcache_count(int type)
array  xcache_info(int type, int id)
array  xcache_list(int type, int id)
void   xcache_clear_cache(int type, int id)
string xcache_coredump(int op_type)

Coverager Functions http://xcache.lighttpd.net/demo/coverager/
array xcache_coverager_decode(string data)
void  xcache_coverager_start([bool clean = true])
void  xcache_coverager_stop([bool clean = false])
array xcache_coverager_get([bool clean = false])

Dis/Assembler? Opcode Functions
string xcache_asm(string filename)
string xcache_dasm_file(string filename)
string xcache_dasm_string(string code)
string xcache_encode(string filename)
bool   xcache_decode(string filename)

string xcache_get_op_type(int op_type)
string xcache_get_data_type(int type)
string xcache_get_opcode(int opcode)
string xcache_get_op_spec(int op_type)
string xcache_get_opcode_spec(int opcode)
mixed  xcache_get_special_value(zval value)
string xcache_is_autoglobal(string name)

