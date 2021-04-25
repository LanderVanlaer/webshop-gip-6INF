<?php

    namespace includes;

    abstract class Error {
        const empty_value = 1;
        const duplicate = 2;
        const file_not_allowed = 3;
        const file_too_large = 4;
    }