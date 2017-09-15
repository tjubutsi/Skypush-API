<?php
	foreach (glob(dirname(__FILE__) . "/entities/*.php") as $filename)
	{
		require_once $filename;
	}

	class entity {
		public $tableName;
		public $entityName;
		public $connection;
		public $parameters;
		public $parameterPlaceholders;
		public $parameterTypes;

		function __construct($tableName, $entityName, $connection) {
			$this->tableName = $tableName;
			$this->entityName = $entityName;
			$this->connection = $connection;

			$reflection = new ReflectionClass($entityName);
			$reflectionProperties = $reflection->getProperties();
      
			$parameters = array();
			$parameterPlaceholders = array();
      
			foreach ($reflectionProperties as $property) {
				array_push($parameters, $property->getName());
				array_push($parameterPlaceholders, "?");
				$this->parameterTypes = $this->parameterTypes . "s";
			}

			$this->parameters = $parameters;
			$this->parameterPlaceholders = implode(", ", $parameterPlaceholders);
		}


		function add($object) {
			$columns = implode(", ", $this->parameters);
			$query = $this->connection->prepare("INSERT INTO {$this->tableName} ({$columns}) VALUES ({$this->parameterPlaceholders})");
			$this->bindParameters($query, $object);
			if (!$query->execute()) {
				return false;
			}
			$object->id = $query->insert_id;
			$query->close();
			return true;
		}

		function update($object) {
			$parameters = array();
			foreach ($this->parameters as $parameter) {
				array_push($parameters, $parameter . " = ?");
			}
			$columns = implode(", ", $parameters);
			$query = $this->connection->prepare("UPDATE {$this->tableName} SET {$columns} WHERE id = {$object->id}");
			$this->bindParameters($query, $object);
			if (!$query->execute()) {
				$query->close();
				return false;
			}
			$query->close();
			return true;
		}

		function delete($object) {
			$query = $this->connection->prepare("DELETE FROM {$this->tableName} WHERE id = {$object->id}");
			$query->execute();
			$query->close();
		}

		function get($id) {
			$result = new $this->entityName;
			$columns = implode(", ", $this->parameters);
			$query = $this->connection->prepare("SELECT {$columns} FROM {$this->tableName} WHERE id = ?");
			foreach ($this->parameters as $parameter) {
				$resultArray[$parameter] = &$result->$parameter;
			}

			call_user_func_array(array($query, "bind_result"), $resultArray);
			$query->bind_param("i", $id);
			$query->execute();
			$query->store_result();
			if ($query->num_rows === 1) {
				$query->fetch();
			}
			else {
				$query->close();
				return false;
			}

			$query->close();
			return $result;
		}

		function getList() {
			$result = array();
			$row = new $this->entityName;
			
			$columns = implode(", ", $this->parameters);
			$query = $this->connection->prepare("SELECT {$columns} FROM {$this->tableName}");
			foreach ($this->parameters as $parameter) {
				$resultArray[$parameter] = &$row->$parameter;
			}

			call_user_func_array(array($query, "bind_result"), $resultArray);
			$query->execute();
			$query->store_result();
			while ($query->fetch()) {
				$result[] = unserialize(serialize($row));;
			}

			$query->close();
			return $result;
		}

		function where($property, $value) {
			$result = new $this->entityName;
			if (!property_exists($result, $property)) {
				return false;
			}
			
			$columns = implode(", ", $this->parameters);
			$query = $this->connection->prepare("SELECT {$columns} FROM {$this->tableName} WHERE {$property} = ?");
			foreach ($this->parameters as $parameter) {
				$resultArray[$parameter] = &$result->$parameter;
			}

			call_user_func_array(array($query, "bind_result"), $resultArray);
			$query->bind_param("s", $value);
			$query->execute();
			$query->store_result();
			if ($query->num_rows === 1) {
				$query->fetch();
			}
			else {
				$query->close();
				return false;
			}

			$query->close();
			return $result;
		}

		function whereList($property, $value) {
			$result = array();
			$row = new $this->entityName;
			
			$columns = implode(", ", $this->parameters);
			$query = $this->connection->prepare("SELECT {$columns} FROM {$this->tableName} WHERE {$property} = ?");
			foreach ($this->parameters as $parameter) {
				$resultArray[$parameter] = &$row->$parameter;
			}

			call_user_func_array(array($query, "bind_result"), $resultArray);
			$query->bind_param("s", $value);
			$query->execute();
			$query->store_result();
			while ($query->fetch()) {
				$result[] = unserialize(serialize($row));;
			}

			$query->close();
			return $result;
		}

		private function bindParameters($query, $object) {
			$parameters = array($this->parameterTypes);

			foreach ($this->parameters as $parameter) {
				array_push($parameters, $object->$parameter);
			}

			$tmp = array();
			foreach ($parameters as $key => $value) {
			    $tmp[$key] = &$parameters[$key];
            }
			
			call_user_func_array(array($query, "bind_param"), $tmp);
		}
	}
