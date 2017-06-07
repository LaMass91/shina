<?
define("LOG_FILENAME", $_SERVER["DOCUMENT_ROOT"] . "/log1c.txt");

AddEventHandler("iblock", "OnAfterIBlockElementAdd", array("Set_prop", "Set"));
class Set_prop
{
    // Вносим характеристики Ширина/высота Радиус в Тайное свойство. 
    // Выбираем из всех торговых предложений элемента характеристики Ширина, Высота и добавляем к элементу в соответствующие характеристики (делаем множественное значение)
    public function Set(&$arSet)
    {
        if ($arSet['IBLOCK_ID'] == 1) {
            $IBLOCK_ID = $arSet['IBLOCK_ID'];

            $sel      = array("ID", "IBLOCK_ID", "PROPERTY_SHIRINA", "PROPERTY_VISOTA", "PROPERTY_DIAMETR", "PROPERTY_CML2_LINK");
            $fil      = array("IBLOCK_ID" => 10, "ACTIVE" => "Y", "PROPERTY_CML2_LINK" => $arSet['ID']);
            $rs       = CIBlockElement::GetList(array(), $fil, false, false, $sel);
            $arObject = array();
            $id_p     = array();
            $id_v     = array();
            $ids      = array();
            $ids_v    = array();
            while ($object = $rs->GetNext()) {
                $arObject[] = $object;
            }

            $property_shirina = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID" => $IBLOCK_ID, "CODE" => "SHIRINA"));
            while ($shirina_fields = $property_shirina->GetNext()) {
                $id_p[] = array("ID" => $shirina_fields["ID"], "VALUE" => $shirina_fields["VALUE"]);
            }

            foreach ($arObject as $prop) {
                foreach ($id_p as $key => $val) {
                    if ($val["VALUE"] == $prop["PROPERTY_SHIRINA_VALUE"]) {
                        $ids[] = $val["ID"];
                    }
                }
            }
            $uniq_id = array_unique($ids);
            CIBlockElement::SetPropertyValuesEx($arSet["ID"], false, array(14 => $uniq_id));

            $property_visota = CIBlockPropertyEnum::GetList(array(), array("IBLOCK_ID" => $IBLOCK_ID, "CODE" => "VISOTA"));
            while ($visota_fields = $property_visota->GetNext()) {
                $id_v[] = array("ID" => $visota_fields["ID"], "VALUE" => $visota_fields["VALUE"]);
            }
            foreach ($arObject as $prop) {
                foreach ($id_v as $valu) {
                    if ($valu["VALUE"] == $prop["PROPERTY_VISOTA_VALUE"]) {
                        $ids_v[] = $valu["ID"];
                    }
                }
            }
            $uniq_id_v = array_unique($ids_v);
            CIBlockElement::SetPropertyValuesEx($arSet["ID"], false, array(15 => $uniq_id_v));

            foreach ($arObject as $prop) {
                $curId = $prop["ID"];

                $stroka = $prop["PROPERTY_SHIRINA_VALUE"] . '/' . $prop["PROPERTY_VISOTA_VALUE"] . " R" . $prop["PROPERTY_DIAMETR_VALUE"];
                CIBlockElement::SetPropertyValuesEx($curId, false, array(92 => $stroka));
                //AddMessage2Log('$dump = '.print_r($stroka, true),'');
            }

            unset($flag);
            unset($ibpenum);
            unset($id_p);
            unset($id_v);
            unset($ids);
            unset($ids_v);
            unset($uniq_id);
            unset($uniq_id_v);
            unset($arObject);
            unset($prop);

        }
    }
}
?>
