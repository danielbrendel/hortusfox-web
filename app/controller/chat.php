<?php

/**
 * Chat controller
 */
class ChatController extends BaseController {
    const INDEX_LAYOUT = 'layout';

	/**
	 * Perform base initialization
	 * 
	 * @return void
	 */
	public function __construct()
	{
		parent::__construct(self::INDEX_LAYOUT);
	}

    /**
	 * Handles URL: /chat
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\ViewHandler|Asatru\View\RedirectHandler
	 */
	public function view_chat($request)
	{
		if (!app('chat_enable')) {
			return redirect('/');
		}

		$user = UserModel::getAuthUser();

		$messages = ChatMsgModel::getChat();

		return parent::view(['content', 'chat'], [
			'user' => $user,
			'messages' => $messages,
			'_refresh_chat' => true
		]);
	}

	/**
	 * Handles URL: /chat/add
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\RedirectHandler
	 */
	public function add_chat_message($request)
	{
		$validator = new Asatru\Controller\PostValidator([
			'message' => 'required'
		]);

		if (!$validator->isValid()) {
			$errorstr = '';
			foreach ($validator->errorMsgs() as $err) {
				$errorstr .= $err . '<br/>';
			}

			FlashMessage::setMsg('error', 'Invalid data given:<br/>' . $errorstr);
			
			return back();
		}

		$message = $request->params()->query('message', null);

		ChatMsgModel::addMessage($message);

		return redirect('/chat');
	}

	/**
	 * Handles URL: /chat/query
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function query_chat_messages($request)
	{
		try {
			$result = [];

			$messages = ChatMsgModel::getLatestMessages();

			foreach ($messages as $message) {
				$result[] = [
					'id' => $message->get('id'),
					'userId' => $message->get('userId'),
					'userName' => UserModel::getNameById($message->get('userId')),
					'message' => $message->get('message'),
					'system' => $message->get('system'),
					'chatcolor' => UserModel::getChatColorForUser($message->get('userId')),
					'created_at' => date('Y-m-h H:i', strtotime($message->get('created_at'))),
					'diffForHumans' => (new Carbon($message->get('created_at')))->diffForHumans(),
				];
			}

			return json([
				'code' => 200,
				'messages' => $result
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /chat/system/message/latest
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function get_latest_system_message($request)
	{
		try {
			$message = ChatMsgModel::getLatestSystemMessage(5);

			if ($message) {
				$message = [
					'id' => $message->get('id'),
					'userId' => $message->get('userId'),
					'userName' => UserModel::getNameById($message->get('userId')),
					'message' => $message->get('message'),
					'created_at' => date('Y-m-h H:i', strtotime($message->get('created_at'))),
					'diffForHumans' => (new Carbon($message->get('created_at')))->diffForHumans(),
				];
			}

			return json([
				'code' => 200,
				'message' => $message
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /chat/typing/update
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function update_chat_typing($request)
	{
		try {
			UserModel::updateChatTyping();
			
			return json([
				'code' => 200
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /chat/typing
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function get_chat_typing_status($request)
	{
		try {
			$status = UserModel::isAnyoneTypingInChat();

			return json([
				'code' => 200,
				'status' => $status
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

    /**
	 * Handles URL: /chat/user/online
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function get_online_users($request)
	{
		try {
			$result = [];

			$users = UserModel::getOnlineUsers();

			foreach ($users as $user) {
				$result[] = [
					'name' => $user->get('name'),
					'typing' => UtilsModule::isTyping($user->get('last_typing'))
				];
			}

			return json([
				'code' => 200,
				'users' => $result
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}

	/**
	 * Handles URL: /chat/messages/count
	 * 
	 * @param Asatru\Controller\ControllerArg $request
	 * @return Asatru\View\JsonHandler
	 */
	public function get_message_count($request)
	{
		try {
			$count = ChatMsgModel::getUnreadCount();

			return json([
				'code' => 200,
				'count' => $count
			]);
		} catch (\Exception $e) {
			return json([
				'code' => 500,
				'msg' => $e->getMessage()
			]);
		}
	}
}
