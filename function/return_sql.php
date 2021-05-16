<?php
function select_sql_1_null($table_name, $where_name){
    return "SELECT * FROM $table_name WHERE $where_name IS NULL;";
}
function select_sql_1($table_name, $where_name, $where_variable){
    return "SELECT * FROM $table_name WHERE $where_name=\"$where_variable\";";
}

function update_sql_1($table_name, $set_name, $set_variable, $where_name, $where_variable){
    return "UPDATE $table_name SET $set_name = $set_variable WHERE $where_name = $where_variable;";
}

function insert_sql($table_name, $column_name, $column_value){
    foreach ( $column_name as $column_name ) {
        $column_name_list .= $column_name;
        $column_name_list .= ",";
    }
    $column_name_list = substr($column_name_list, 0, -1); //最後の区切り文字を削除
    foreach ( $column_value as $column_value ) {
        if($column_value == "0" || $column_value == "1"){
            $column_value_list .= $column_value;
        }
        else{
            $column_value_list .= "'";
        $column_value_list .= $column_value;
        $column_value_list .= "'";
        }
        $column_value_list .= ",";
    }
    $column_value_list = substr($column_value_list, 0, -1); //最後の区切り文字を削除
    return "INSERT INTO $table_name ($column_name_list) VALUES ($column_value_list);";
}
?>