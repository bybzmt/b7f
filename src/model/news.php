<?php

namespace model;

use b7f;
use mapper;

class news
{
	/**
	 * 添加新闻
	 *
	 * @return 新闻id
	 */
	public function add($type, $title, $content)
	{
		$row = $this->getMapper()->create(0, $type, $title, $content);

		$row->save();

		return $row->id;
	}

	/**
	 * 编辑新闻
	 *
	 * @return 0 成功
	 *         1 该新闻不存在
	 */
	public function edit($id, $type, $title, $content)
	{
		$row = $this->getMapper()->find($id);

		if (!$row) {
			return 1;
		}

		$row->type = $type;
		$row->title = $title;
		$row->content = $content;

		$row->save();

		return 0;
	}

	public function getMapper()
	{
		return mapper\news::getInstance();
	}
}
