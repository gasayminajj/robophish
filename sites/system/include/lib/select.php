<?php
class Select extends DropDown
{
	final public function Make()
	 {
		$tag = new \MaxieSystems\HTML\Select('multiple', $this->GetOption('multiple'));
		foreach(['id', 'name', 'class', 'title'] as $i) if($o = $this->GetOption($i)) $tag->SetAttr($i, $o);
		if(($o = $this->GetOption('size')) > 1) $tag->SetAttr('size', $o);
		if($o = $this->GetOption('data_x')) $tag->SetData($o);
		$html = $this->GetItemsHTML();
		if($this->IsDisabled()) $tag->SetAttr('disabled', true);
		return $tag->SetHTML($html);
	 }

	protected function OnCreate()
	 {
		$this->AddOptionsMeta(['multiple' => ['type' => 'bool', 'value' => false], 'size' => ['type' => 'int,gt0', 'value' => 1]]);
		parent::OnCreate();
	 }

	final protected function MakeItem($selected, $value, $title, Iterator $data = null, $index)
	 {
		$s = "$data";
		if($selected) $s .= ' selected="selected"';
		$v = Filter::TextAttribute($value);
		return "<option value='$v'$s>$title</option>";
	 }

	final protected function OpenGroup($id, $title)
	 {
		return '<optgroup label="'.Filter::TextAttribute($title).'">';
	 }

	final protected function CloseGroup()
	 {
		return '</optgroup>';
	 }
}
?>