<?php
/**
 * @package		UKRGB
 * @subpackage	plg_content_ukrgb_riverguide
 * @license		GNU General Public License version 2 or later; see LICENSE.txt
 */

defined('JPATH_BASE') or die;

jimport('joomla.utilities.date');

/**
 * The UKRGB River guide plugin.
 *
 * @package		UKRGB
 * @subpackage	River guide
 * @version		0.0.1
 */
class plgContentUkrgb_Riverguide extends JPlugin
{
	/**
	 * Constructor
	 *
	 * @access      protected
	 * @param       object  $subject The object to observe
	 * @param       array   $config  An array that holds the plugin configuration
	 * @since       2.5
	 */
	public function __construct(& $subject, $config)
	{				
		parent::__construct($subject, $config);
		$this->loadLanguage();
	}

	/**
	 * @param	string	$context	The context for the data
	 * @param	int		$data		The user id
	 * @param	object
	 *
	 * @return	boolean
	 * @since	2.5
	 */
	function onContentPrepareData($context, $data)
	{
		//error_log("onContentPrepareData");
		if (is_object($data))
		{
			$articleId = isset($data->id) ? $data->id : 0;
			
			if (!isset($data->riverguide) and $articleId > 0)
			{
				// Load the profile data from the database.
					
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->select(array('a.map_id', 'a.grade', 
						'X(b.point)', 'Y(b.point)', 'b.type', 'b.description'));
				$query->from('#__ukrgb_riverguides AS a');
				$query->join('RIGHT', '#__ukrgb_map_point AS b ON (a.id = b.riverguide)');
				$query->where('a.article_id = ' . $db->Quote($articleId));
				$db->setQuery($query);
				$results = $db->loadObjectList();
				
				
				echo ("<br>------------------------<br>");
				var_dump($result);
				echo ("<br>------------------------<br>");
				
				// Check for a database error.
				if ($db->getErrorNum())
				{
					$this->_subject->setError($db->getErrorMsg());
					return false;
				}
				
				// Merge the profile data.
				$data->riverguide = array(
						'putin' => '',
						'takeout' => '',
						'mapid' => $result[2],
						'grade' => $result[3],);
				
			} else {
				//TODO 
				error_log("-- Load the form river data ");
				
				/* // load the form
				JForm::addFormPath(dirname(__FILE__) . '/riverguide');
				$form = new JForm('com_content.article');
				$form->loadFile('ukrgb_riverguide', false);
				
				// Merge the default values
				$data->riverguide = array();
				foreach ($form->getFieldset('riverguide') as $field) {
					$data->riverguide[] = array($field->fieldname, $field->value);
					}
				 */
				
			}
		}

		return true;
	}

	/**
	 * @param	JForm	$form	The form to be altered.
	 * @param	array	$data	The associated data for the form.
	 *
	 * @return	boolean
	 * @since	2.5
	 */
	function onContentPrepareForm($form, $data)
	{
		error_log("onContentPrepareForm");
		if (!($form instanceof JForm))
		{
			$this->_subject->setError('JERROR_NOT_A_FORM');
			return false;
		}

		// Add the extra fields to the form.
		// need a seperate directory for the installer not to consider the XML a package when "discovering"
		JForm::addFormPath(dirname(__FILE__) . '/riverguide');
		$form->loadFile('ukrgb_riverguide', false);
		return true;

	}

	/**
	 * Example after save content method
	 * Article is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the content is saved
	 *
	 * @param	string		The context of the content passed to the plugin (added in 1.6)
	 * @param	object		A JTableContent object
	 * @param	bool		If the content is just about to be created
	 * @since	2.5
	 */
	public function onContentAfterSave($context, &$article, $isNew)
	{	
		error_log("onContentAfterSave  - ");				

		$articleId = $article->id;
		if ($articleId && isset($article->riverguide) && (count($article->riverguide)))
		{
			error_log($article->riverguide['grade']);
			try
			{
				$fields = array(						
						'map_id = \''.$article->riverguide['mapid'].'\'',
						'grade = \''.$article->riverguide['grade'].'\'',
						'putin_pointid = \''.$article->riverguide['putin'].'\'',
						'takeout_pointid = \''.$article->riverguide['takeout'].'\''
						);
		
				$db = JFactory::getDbo();
				$query = $db->getQuery(true);
				$query->update('#__ukrgb_riverguides');
				$query->set($fields); 
				$query->where('article_id = ' . $db->Quote($articleId));
				
				$db->setQuery($query);

				if (!$db->query()) {
					throw new Exception($db->getErrorMsg());
				}
			}
			catch (JException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
			
		}
			
		return true;
	}

	/**
	 * Finder after delete content method
	 * Article is passed by reference, but after the save, so no changes will be saved.
	 * Method is called right after the content is saved
	 *
	 * @param	string		The context of the content passed to the plugin (added in 1.6)
	 * @param	object		A JTableContent object
	 * @since   2.5
	 */
	public function onContentAfterDelete($context, $article)
	{		
		//error_log("onContentAfterDelete");
		
		// TODO
		$articleId	= $article->id;
		if ($articleId)
		{
			try
			{
				$db = JFactory::getDbo();

				$query = $db->getQuery(true);
				$query->delete();
				$query->from('#__user_profiles');
				$query->where('user_id = ' . $db->Quote($articleId));
				$query->where('profile_key LIKE ' . $db->Quote('rating.%'));
				$db->setQuery($query);

				if (!$db->query())
				{
					throw new Exception($db->getErrorMsg());
				}
			}
			catch (JException $e)
			{
				$this->_subject->setError($e->getMessage());
				return false;
			}
		}

		return true;
	}
	
	public function onContentPrepare($context, &$article, &$params, $page = 0)
	{		
		if (!isset($article->riverguide) || !count($article->riverguide) || $article->riverguide['grade'] === NULL)
//		if (!isset($article->riverguide) || !count($article->riverguide))
			return;
		
		error_log(count($article->riverguide));
		
		// add extra css for table
		$doc = JFactory::getDocument();
		$doc->addStyleSheet(JURI::base(true).'/plugins/content/ukrgb_riverguide/riverguide/ukrgb_riverguide.css');
				
		// construct a result table on the fly	
		jimport('joomla.html.grid');
		$table = new JGrid();

		// Create columns
		$table->addColumn('attr')
			->addColumn('value');	

		// populate
		$rownr = 0;
		foreach ($article->riverguide as $attr => $value) {
			$table->addRow(array('class' => 'row'.($rownr % 2)));
			$table->setRowCell('attr', $attr);
			$table->setRowCell('value', $value);
			$rownr++;
		}

		// wrap table in a classed <div>
		$suffix = $this->params->get('riverguideclass_sfx', 'riverguide');
		$html = '<div class="'.$suffix.'">'.(string)$table.'</div>';

		$article->text = $html.$article->text;
	}
	
}
