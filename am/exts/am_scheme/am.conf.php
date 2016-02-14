<?php
/**
 * Amathista - PHP Framework
 *
 * @author Alex J. Rondón <arondn2@gmail.com>
 * 
 */

return array(
  
  /**
   * ---------------------------------------------------------------------------
   * Archivos de la extensión
   * ---------------------------------------------------------------------------
   */
  'files' => array(
    'AmField.class',
    'AmRelation.class',
    'AmScheme.class',
    'AmQuery.class',
    'AmTable.class',
    'AmModel.class',
    'AmValidator.class'
  ),

  /**
   * ---------------------------------------------------------------------------
   * Configuración a extender
   * ---------------------------------------------------------------------------
   */
  'extend' => array(
    'models' => array(
      '' => array(
        'models' => array()
      )
    ),

    'schemes' => array(),

    'validators' => array(
      'messages' => array()
    ),

    // Formatos
    'formats' => array(
      'AMSCHEME_QUERY_TYPE_UNKNOW' => 'AmScheme: Tipo de consulta indefinida "%s"',
      'AMSCHEME_FILE_NOT_FOUND' => 'AmScheme: No se encontró el archivo "%s"',
      'AMSCHEME_SCHEMECONF_NOT_FOUND' => 'AmScheme: No se encontró la configuración para la fuente "%s"',
      'AMSCHEMA_TABLE_ALREADY_HAVE_A_FIELD_NAMED' => 'AmScheme: La tabla "%s" ya tiene un campo llamado "%s"',
      'AMSCHEME_MODEL_WITHOUT_TABLE' => 'AmScheme: Modelo "%s" sin tabla',
      'AMSCHEME_MODEL_DONT_HAVE_PK' => 'AmScheme: Model "%s" no tiene primary key',
    )
  ),
  
  /**
   * ---------------------------------------------------------------------------
   * Archivos de la extensión
   * ---------------------------------------------------------------------------
   */
  'requires' => array(
    'exts/am_coder/',
  ),

  /**
   * ---------------------------------------------------------------------------
   * Métodos de mezcla de configuación
   * ---------------------------------------------------------------------------
   */
  'mergeFunctions' => array(
    'models' => 'array_merge_recursive',
    'schemes' => 'array_merge_recursive',
    'validators' => 'array_merge_recursive',
  )
  
);